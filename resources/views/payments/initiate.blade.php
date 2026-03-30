@extends('layouts.app')
@section('title', 'Complete Payment')

@section('content')
<div class="max-w-lg mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Complete Payment</h1>
        <p class="text-gray-500 mt-1">Booking #{{ $booking->id }}</p>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">Order Summary</h3>
        <div class="flex gap-4 mb-4">
            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100">
                @if($booking->dress->primaryImage())
                    <img src="{{ $booking->dress->primaryImage()->url }}" class="w-full h-full object-cover" alt="">
                @endif
            </div>
            <div>
                <div class="font-bold text-gray-900">{{ $booking->dress->name }}</div>
                <div class="text-sm text-gray-500">{{ $booking->total_days }} days</div>
                <div class="text-sm text-gray-500">
                    {{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }}
                    → {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}
                </div>
            </div>
        </div>
        <div class="space-y-2 text-sm border-t border-gray-100 pt-3">
            <div class="flex justify-between"><span class="text-gray-500">Rental</span><span>₨{{ number_format($booking->rental_amount) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Deposit</span><span>₨{{ number_format($booking->deposit_amount) }}</span></div>
            <div class="flex justify-between font-bold text-base border-t border-gray-100 pt-2"><span>Advance Payment (50%)</span><span class="text-primary-600">₨{{ number_format($booking->advance_amount) }}</span></div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="space-y-3">
        <!-- eSewa -->
        <form method="POST" action="{{ route('payment.esewa.init', $booking) }}">
            @csrf
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-3 transition-colors shadow-md">
                <span class="text-2xl">💚</span>
                <span>Pay with eSewa</span>
                <span class="text-green-200 text-sm">₨{{ number_format($booking->advance_amount) }}</span>
            </button>
        </form>

        <!-- Khalti -->
        <form method="POST" action="{{ route('payment.khalti.init', $booking) }}">
            @csrf
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-3 transition-colors shadow-md">
                <span class="text-2xl">💜</span>
                <span>Pay with Khalti</span>
                <span class="text-purple-200 text-sm">₨{{ number_format($booking->advance_amount) }}</span>
            </button>
        </form>

        <!-- Offline / Cash -->
        <div class="relative">
            <div class="absolute inset-x-0 -top-2 flex items-center justify-center">
                <span class="bg-white px-2 text-xs text-gray-400">or</span>
            </div>
        </div>
        <form method="POST" action="{{ route('payment.offline.init', $booking) }}">
            @csrf
            <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-4 rounded-2xl flex items-center justify-center gap-3 transition-colors border border-gray-200">
                <span class="text-2xl">🏦</span>
                <span>Pay Offline (Cash / Bank Transfer)</span>
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-4">
        Online payments are secured by eSewa &amp; Khalti. For offline payment, our team will contact you to arrange the transaction.
    </p>
</div>
@endsection
