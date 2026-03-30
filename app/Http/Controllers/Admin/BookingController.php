<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingStatusUpdated;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'dress.images'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                ->orWhereHas('dress', fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $bookings = $query->paginate(15)->withQueryString();

        $statuses = ['pending', 'paid', 'active', 'returned', 'completed', 'cancelled'];

        return view('admin.bookings.index', compact('bookings', 'statuses'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'dress.images', 'payments', 'ornaments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status'          => 'required|in:pending,paid,active,returned,completed,cancelled',
            'notes'           => 'nullable|string',
            'fine_amount'     => 'nullable|numeric|min:0',
            'discount_type'   => 'nullable|in:none,fixed,percentage',
            'discount_amount' => [
                'nullable', 'numeric', 'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percentage' && $value > 100) {
                        $fail('Discount percentage cannot exceed 100%.');
                    }
                },
            ],
        ]);

        $previousStatus = $booking->status;

        if ($data['status'] === 'returned' && ! $booking->returned_at) {
            $data['returned_at'] = now();
        }

        // Recalculate total_amount when discount is updated
        $discountType   = $data['discount_type']   ?? $booking->discount_type;
        $discountAmount = $data['discount_amount']  ?? $booking->discount_amount;
        $baseAmount     = $booking->rental_amount + $booking->deposit_amount;

        if ($discountType === 'percentage') {
            $discountApplied = round($baseAmount * ($discountAmount / 100), 2);
        } elseif ($discountType === 'fixed') {
            $discountApplied = min((float) $discountAmount, $baseAmount);
        } else {
            $discountApplied = 0;
        }

        $data['total_amount'] = max(0, $baseAmount - $discountApplied);

        $booking->update($data);

        if ($booking->status !== $previousStatus) {
            try {
                Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking->load(['dress', 'user']), $previousStatus));
            } catch (\Exception) {
                // Non-fatal: status is updated, email delivery failure should not block admin flow
            }
        }

        return back()->with('success', 'Booking status updated.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,paid,active,returned,completed,cancelled']);
        $previousStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        if ($booking->status !== $previousStatus) {
            try {
                Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking->load(['dress', 'user']), $previousStatus));
            } catch (\Exception) {
                // Non-fatal: status is updated, email delivery failure should not block admin flow
            }
        }

        return back()->with('success', 'Status updated to ' . $request->status);
    }
}
