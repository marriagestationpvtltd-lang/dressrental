<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

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
        $booking->load(['user', 'dress.images', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status'     => 'required|in:pending,paid,active,returned,completed,cancelled',
            'notes'      => 'nullable|string',
            'fine_amount'=> 'nullable|numeric|min:0',
        ]);

        if ($data['status'] === 'returned' && ! $booking->returned_at) {
            $data['returned_at'] = now();
        }

        $booking->update($data);

        return back()->with('success', 'Booking status updated.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,paid,active,returned,completed,cancelled']);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Status updated to ' . $request->status);
    }
}
