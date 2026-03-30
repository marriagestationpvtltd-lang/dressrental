@extends('layouts.app')
@section('title', 'Order Submitted')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-3">Order Submitted! 🎊</h1>
    <p class="text-gray-500 mb-2">Booking #{{ $booking->id }} has been received.</p>
    <p class="text-gray-500 mb-8">Our team will contact you shortly to confirm your booking and arrange payment.</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 text-left">
        <h3 class="font-bold text-gray-900 mb-3">Booking Details</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Dress</span>
                <span class="font-medium">{{ $booking->dress->name ?? '' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Period</span>
                <span>{{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }} → {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Advance Amount</span>
                <span class="font-bold text-yellow-600">₨{{ number_format($booking->advance_amount) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Payment Method</span>
                <span class="font-medium">Offline / Cash</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending Admin Confirmation</span>
            </div>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-8 text-left text-sm text-blue-700">
        <p class="font-semibold mb-1">What happens next?</p>
        <p>Our team will reach out to you via phone or email to discuss the booking and payment arrangements. Please keep your contact details handy.</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('bookings.show', $booking) }}" class="bg-primary-600 text-white font-bold px-8 py-3 rounded-2xl hover:bg-primary-700">
            View Booking
        </a>
        <a href="{{ route('dresses.index') }}" class="border border-gray-200 text-gray-700 font-medium px-8 py-3 rounded-2xl hover:bg-gray-50">
            Browse More
        </a>
    </div>
</div>
@endsection
