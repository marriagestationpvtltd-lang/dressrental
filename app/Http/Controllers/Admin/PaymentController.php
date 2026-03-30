<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'booking.dress'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->paginate(15)->withQueryString();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalRevenue'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'booking.dress']);
        return view('admin.payments.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'status'         => 'required|in:pending,completed,failed,refunded',
            'payment_method' => 'required|in:esewa,khalti,cash',
            'amount'         => 'required|numeric|min:0',
            'transaction_id' => 'nullable|string|max:255|unique:payments,transaction_id,' . $payment->id,
            'remarks'        => 'nullable|string|max:1000',
        ]);

        $wasCompleted = $payment->status === 'completed';

        if ($data['status'] === 'completed' && ! $wasCompleted && ! $payment->verified_at) {
            $data['verified_at'] = now();
        }

        $payment->update($data);

        // Keep booking in sync: mark as paid when advance payment is completed
        if ($data['status'] === 'completed' && ! $wasCompleted) {
            $this->syncBookingStatusOnPaymentCompletion($payment);
        }

        return back()->with('success', 'Payment updated successfully.');
    }

    public function refund(Request $request, Payment $payment)
    {
        $request->validate(['remarks' => 'nullable|string']);

        if ($payment->status !== 'completed') {
            return back()->withErrors(['error' => 'Only completed payments can be refunded.']);
        }

        Payment::create([
            'booking_id'     => $payment->booking_id,
            'user_id'        => $payment->user_id,
            'amount'         => $payment->amount,
            'payment_method' => $payment->payment_method,
            'status'         => 'completed',
            'payment_type'   => 'deposit_refund',
            'remarks'        => $request->remarks ?? 'Deposit refund',
            'verified_at'    => now(),
        ]);

        $payment->update(['status' => 'refunded', 'remarks' => $request->remarks]);

        $payment->booking->update(['status' => 'completed']);

        return back()->with('success', 'Deposit refunded and booking completed.');
    }

    public function approve(Request $request, Payment $payment)
    {
        $request->validate(['remarks' => 'nullable|string|max:500']);

        if ($payment->status !== 'pending' || $payment->payment_method !== 'cash') {
            return back()->withErrors(['error' => 'Only pending offline (cash) payments can be approved.']);
        }

        $payment->update([
            'status'      => 'completed',
            'verified_at' => now(),
            'remarks'     => $request->remarks ?? 'Approved by admin.',
        ]);

        $this->syncBookingStatusOnPaymentCompletion($payment);

        return back()->with('success', 'Offline payment approved and booking marked as paid.');
    }

    private function syncBookingStatusOnPaymentCompletion(Payment $payment): void
    {
        if ($payment->payment_type === 'advance'
            && $payment->booking
            && $payment->booking->status === 'pending') {
            $payment->booking->update(['status' => 'paid', 'paid_at' => now()]);
        }
    }
}
