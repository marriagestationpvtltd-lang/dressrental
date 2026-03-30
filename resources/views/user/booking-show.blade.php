@extends('layouts.app')
@section('title', 'Booking #' . $booking->id)

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('bookings.index') }}" class="text-primary-600 hover:text-primary-700">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
        <span class="ml-auto px-3 py-1 rounded-full text-sm font-medium bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    <!-- Dress Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <div class="flex gap-4">
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                @if($booking->dress && $booking->dress->primaryImage())
                    <img src="{{ $booking->dress->primaryImage()->url }}" class="w-full h-full object-cover" alt="">
                @endif
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ $booking->dress->name ?? 'N/A' }}</h3>
                <p class="text-sm text-gray-500">Size: {{ $booking->dress->size ?? '' }}</p>
                <p class="text-sm text-gray-500">Category: {{ $booking->dress->category->name ?? '' }}</p>
            </div>
        </div>
    </div>

    <!-- Dates -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h3 class="font-semibold text-gray-900 mb-4">Rental Period</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-500 mb-1">Start Date</div>
                <div class="font-bold text-gray-900">{{ $booking->bs_start_date ? $booking->bs_start_date . ' BS' : '' }}</div>
                <div class="text-sm text-gray-500">{{ $booking->start_date->format('M d, Y') }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-500 mb-1">End Date</div>
                <div class="font-bold text-gray-900">{{ $booking->bs_end_date ? $booking->bs_end_date . ' BS' : '' }}</div>
                <div class="text-sm text-gray-500">{{ $booking->end_date->format('M d, Y') }}</div>
            </div>
        </div>
        <div class="mt-3 text-center text-sm text-primary-600 font-medium">
            {{ $booking->total_days }} days total
        </div>
    </div>

    <!-- Payment Breakdown -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h3 class="font-semibold text-gray-900 mb-4">Payment Breakdown</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Rental Amount</span><span class="font-medium">₨{{ number_format($booking->rental_amount) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Deposit</span><span class="font-medium">₨{{ number_format($booking->deposit_amount) }}</span></div>
            <div class="border-t border-gray-100 pt-2 flex justify-between font-bold"><span>Total Amount</span><span>₨{{ number_format($booking->total_amount) }}</span></div>
            <div class="flex justify-between text-primary-600"><span>Advance Paid (50%)</span><span class="font-bold">₨{{ number_format($booking->advance_amount) }}</span></div>
            @if($booking->fine_amount > 0)
            <div class="flex justify-between text-red-600"><span>Fine</span><span class="font-bold">₨{{ number_format($booking->fine_amount) }}</span></div>
            @endif
        </div>
    </div>

    <!-- Payments History -->
    @if($booking->payments->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h3 class="font-semibold text-gray-900 mb-4">Payment History</h3>
        <div class="space-y-3">
            @foreach($booking->payments as $payment)
            <div class="flex justify-between items-center text-sm">
                <div>
                    <span class="font-medium capitalize">{{ $payment->payment_type }}</span>
                    <span class="text-gray-500 ml-2">via {{ strtoupper($payment->payment_method) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold">₨{{ number_format($payment->amount) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $payment->status_badge_color }}-100 text-{{ $payment->status_badge_color }}-700">{{ $payment->status }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Actions -->
    @if($booking->status === 'pending')
    <a href="{{ route('payment.initiate', $booking) }}"
       class="block w-full text-center bg-primary-600 text-white font-bold py-4 rounded-2xl hover:bg-primary-700 transition-colors shadow-lg">
        Complete Payment
    </a>
    @endif
</div>
@endsection
