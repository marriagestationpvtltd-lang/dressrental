<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class InvoiceController extends Controller
{
    public function show(Booking $booking)
    {
        $booking->load(['user', 'dress.images', 'payments']);
        return view('admin.bookings.invoice', compact('booking'));
    }
}
