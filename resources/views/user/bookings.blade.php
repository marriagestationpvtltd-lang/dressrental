@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')

<!-- Header -->
<div class="gradient-hero text-white">
    <div class="max-w-4xl mx-auto px-4 py-10 md:py-14">
        <h1 class="text-2xl md:text-3xl font-extrabold">My Bookings</h1>
        <p class="text-violet-300 text-sm mt-1">Track and manage all your dress rentals</p>
    </div>
    <div class="h-6 overflow-hidden">
        <svg viewBox="0 0 1440 24" class="w-full" preserveAspectRatio="none" fill="#f9fafb">
            <path d="M0,24 C360,0 1080,0 1440,24 L1440,24 L0,24 Z"/>
        </svg>
    </div>
</div>

<div class="bg-gray-50 min-h-screen pb-10">
    <div class="max-w-4xl mx-auto px-4 py-6">

        @if($bookings->count())
        <div class="space-y-4">
            @foreach($bookings as $booking)
            <div class="bg-white rounded-2xl shadow-card border border-violet-100 hover:border-primary-200 hover:shadow-card-hover transition-all overflow-hidden">
                <!-- Status top bar -->
                <div class="h-1 bg-{{ $booking->status_badge_color }}-400"></div>

                <div class="flex items-start gap-4 p-5">
                    <!-- Dress image -->
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl overflow-hidden bg-gradient-to-br from-violet-100 to-pink-100 border-2 border-violet-100 shrink-0">
                        @if($booking->dress && $booking->dress->primaryImage())
                            <img src="{{ $booking->dress->primaryImage()->url }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-3xl">👗</div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-extrabold text-gray-900 truncate text-sm md:text-base">{{ $booking->dress->name ?? 'N/A' }}</h3>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $booking->dress->category->name ?? '' }}</div>
                            </div>
                            <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-bold border
                                bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700 border-{{ $booking->status_badge_color }}-200">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <!-- Date row -->
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-2 bg-gray-50 border border-gray-100 rounded-lg px-3 py-1.5 w-fit">
                            <svg class="w-3.5 h-3.5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }}
                            <span class="text-gray-400">→</span>
                            {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}
                            <span class="text-primary-600 font-bold ml-1">({{ $booking->total_days }} days)</span>
                        </div>

                        <!-- Pricing & actions row -->
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xs text-gray-400 font-medium">Total</span>
                                <span class="font-extrabold text-gray-900 text-sm md:text-base">₨{{ number_format($booking->total_amount) }}</span>
                            </div>
                            <div class="flex gap-2">
                                @if($booking->status === 'pending')
                                    <a href="{{ route('payment.initiate', $booking) }}"
                                       class="gradient-bg text-white text-xs font-bold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity shadow-sm flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        Pay Now
                                    </a>
                                @endif
                                <a href="{{ route('bookings.show', $booking) }}"
                                   class="border-2 border-violet-200 text-primary-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-violet-50 transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $bookings->links() }}</div>

        @else
        <div class="text-center py-20 bg-white rounded-3xl border border-violet-100 shadow-card">
            <div class="w-24 h-24 bg-violet-50 border-2 border-violet-100 rounded-3xl flex items-center justify-center text-5xl mx-auto mb-5">📋</div>
            <h2 class="text-xl font-extrabold text-gray-900 mb-2">No Bookings Yet</h2>
            <p class="text-gray-400 text-sm mb-8 max-w-xs mx-auto">Browse our collection and book your perfect dress for any occasion</p>
            <a href="{{ route('dresses.index') }}" class="inline-flex items-center gap-2 gradient-bg text-white px-9 py-4 rounded-2xl font-extrabold hover:opacity-90 shadow-glow-primary transition-opacity">
                Browse Dresses
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
