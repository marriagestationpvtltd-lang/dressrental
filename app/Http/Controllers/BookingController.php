<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Dress;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function checkAvailability(Request $request, Dress $dress)
    {
        $request->validate([
            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'booked_size' => 'nullable|in:XS,S,M,L,XL,XXL,Free Size',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $size      = $request->input('booked_size');

        $dress->load('pricings');
        $available = $this->bookingService->isAvailable($dress->id, $startDate, $endDate, $size);
        $amounts   = $available
            ? $this->bookingService->calculateRentalAmount($dress, $startDate, $endDate)
            : null;

        return response()->json([
            'available' => $available,
            'amounts'   => $amounts,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dress_id'    => 'required|exists:dresses,id',
            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'booked_size' => 'nullable|in:XS,S,M,L,XL,XXL,Free Size',
            'notes'       => 'nullable|string|max:500',
            'ornaments'   => 'nullable|array',
            'ornaments.*' => 'integer|exists:ornaments,id',
        ]);

        $data['user_id'] = Auth::id();

        try {
            $booking = $this->bookingService->createBooking($data);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        try {
            Mail::to($booking->user->email)->send(new BookingConfirmation($booking->load(['dress', 'user', 'ornaments'])));
        } catch (\Exception) {
            // Non-fatal: booking is created, email delivery failure should not block user flow
        }

        return redirect()->route('payment.initiate', $booking)
            ->with('success', 'Booking created! Please complete payment.');
    }
}
