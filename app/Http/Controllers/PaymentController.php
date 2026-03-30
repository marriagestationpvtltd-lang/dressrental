<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\EsewaService;
use App\Services\KhaltiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        protected EsewaService  $esewa,
        protected KhaltiService $khalti
    ) {}

    public function initiate(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($booking->status, ['pending'])) {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'This booking cannot be paid.');
        }

        return view('payments.initiate', compact('booking'));
    }

    public function esewaInit(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $transactionUuid = 'DR-' . $booking->id . '-' . Str::random(8);

        $payment = Payment::create([
            'booking_id'     => $booking->id,
            'user_id'        => auth()->id(),
            'amount'         => $booking->advance_amount,
            'payment_method' => 'esewa',
            'transaction_id' => $transactionUuid,
            'status'         => 'pending',
            'payment_type'   => 'advance',
        ]);

        $formData = $this->esewa->buildFormData([
            'amount'           => $booking->advance_amount,
            'total_amount'     => $booking->advance_amount,
            'transaction_uuid' => $transactionUuid,
            'success_url'      => route('payment.esewa.verify', ['payment' => $payment->id]),
            'failure_url'      => route('payment.failure', ['booking' => $booking->id]),
        ]);

        $formData['action'] = $this->esewa->getPaymentUrl();

        return view('payments.esewa-form', compact('formData', 'booking', 'payment'));
    }

    public function esewaVerify(Request $request, Payment $payment)
    {
        $encodedData = $request->get('data');

        if (! $encodedData) {
            return redirect()->route('payment.failure', ['booking' => $payment->booking_id])
                ->withErrors(['error' => 'No payment data received.']);
        }

        $result = $this->esewa->verify($encodedData);

        if ($result['success']) {
            $payment->update([
                'status'           => 'completed',
                'gateway_response' => $result['data'] ?? [],
                'verified_at'      => now(),
            ]);

            $booking = $payment->booking;
            $booking->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->route('payment.success', ['booking' => $booking->id])
                ->with('success', 'Payment successful!');
        }

        $payment->update(['status' => 'failed', 'gateway_response' => $result['data'] ?? []]);

        return redirect()->route('payment.failure', ['booking' => $payment->booking_id])
            ->withErrors(['error' => 'Payment verification failed.']);
    }

    public function khaltiInit(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $user = auth()->user();

        $result = $this->khalti->initiatePayment([
            'return_url'      => route('payment.khalti.verify'),
            'amount'          => $booking->advance_amount,
            'order_id'        => 'DR-' . $booking->id . '-' . Str::random(6),
            'order_name'      => 'Dress Rental #' . $booking->id,
            'customer_name'   => $user->name,
            'customer_email'  => $user->email,
            'customer_phone'  => $user->phone ?? '',
        ]);

        if ($result['success']) {
            $pidx = $result['data']['pidx'] ?? null;
            $paymentUrl = $result['data']['payment_url'] ?? null;

            Payment::create([
                'booking_id'       => $booking->id,
                'user_id'          => auth()->id(),
                'amount'           => $booking->advance_amount,
                'payment_method'   => 'khalti',
                'transaction_id'   => $pidx,
                'status'           => 'pending',
                'payment_type'     => 'advance',
                'gateway_response' => $result['data'],
            ]);

            if ($paymentUrl) {
                return redirect($paymentUrl);
            }
        }

        return back()->withErrors(['error' => 'Failed to initiate Khalti payment.']);
    }

    public function khaltiVerify(Request $request)
    {
        $pidx = $request->get('pidx');
        $status = $request->get('status');

        if ($status !== 'Completed' || ! $pidx) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Payment was not completed.']);
        }

        $payment = Payment::where('transaction_id', $pidx)->firstOrFail();

        $result = $this->khalti->verify($pidx);

        if ($result['success']) {
            $payment->update([
                'status'           => 'completed',
                'gateway_response' => $result['data'] ?? [],
                'verified_at'      => now(),
            ]);

            $booking = $payment->booking;
            $booking->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->route('payment.success', ['booking' => $booking->id])
                ->with('success', 'Khalti payment successful!');
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('payment.failure', ['booking' => $payment->booking_id])
            ->withErrors(['error' => 'Khalti verification failed.']);
    }

    public function offlineInit(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($booking->status, ['pending'])) {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'This booking cannot be paid.');
        }

        // Prevent duplicate offline payment records for the same booking
        $existingOfflinePayment = Payment::where('booking_id', $booking->id)
            ->where('payment_method', 'cash')
            ->where('status', 'pending')
            ->exists();

        if ($existingOfflinePayment) {
            return redirect()->route('payment.offline.confirm', $booking)
                ->with('info', 'You already have a pending offline payment for this booking.');
        }

        Payment::create([
            'booking_id'   => $booking->id,
            'user_id'      => auth()->id(),
            'amount'       => $booking->advance_amount,
            'payment_method' => 'cash',
            'status'       => 'pending',
            'payment_type' => 'advance',
            'remarks'      => 'Offline payment — awaiting admin confirmation.',
        ]);

        return redirect()->route('payment.offline.confirm', $booking)
            ->with('success', 'Your order has been submitted. Our team will contact you shortly.');
    }

    public function offlineConfirm(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payments.offline-confirm', compact('booking'));
    }

    public function success(Request $request)
    {
        $booking = \App\Models\Booking::findOrFail($request->booking);
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
        return view('payments.success', compact('booking'));
    }

    public function failure(Request $request)
    {
        $booking = \App\Models\Booking::findOrFail($request->booking);
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
        return view('payments.failure', compact('booking'));
    }
}
