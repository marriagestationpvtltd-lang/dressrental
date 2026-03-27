@extends('layouts.app')
@section('title', 'Payment Successful')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-3">Payment Successful! 🎉</h1>
    <p class="text-gray-500 mb-8">Your booking #{{ $booking->id }} has been confirmed.</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 text-left">
        <h3 class="font-bold text-gray-900 mb-3">Booking Details</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Dress</span><span class="font-medium">{{ $booking->dress->name ?? '' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Period</span><span>{{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }} → {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Amount Paid</span><span class="font-bold text-green-600">₨{{ number_format($booking->advance_amount) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Remaining</span><span>₨{{ number_format($booking->total_amount - $booking->advance_amount) }}</span></div>
        </div>
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
