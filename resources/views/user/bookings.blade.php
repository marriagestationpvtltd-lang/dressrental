@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Bookings</h1>

    @if($bookings->count())
    <div class="space-y-4">
        @foreach($bookings as $booking)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-start gap-4 p-4">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                    @if($booking->dress && $booking->dress->primaryImage())
                        <img src="{{ asset('storage/' . $booking->dress->primaryImage()->image_path) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl">👗</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-bold text-gray-900 truncate">{{ $booking->dress->name ?? 'N/A' }}</h3>
                        <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 mt-1">
                        📅 {{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }}
                        → {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}
                        ({{ $booking->total_days }} days)
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <div class="text-sm">
                            <span class="text-gray-500">Total:</span>
                            <span class="font-bold text-gray-900">₨{{ number_format($booking->total_amount) }}</span>
                        </div>
                        <div class="flex gap-2">
                            @if($booking->status === 'pending')
                                <a href="{{ route('payment.initiate', $booking) }}"
                                   class="bg-primary-600 text-white text-xs px-4 py-2 rounded-lg font-medium hover:bg-primary-700">
                                    Pay Now
                                </a>
                            @endif
                            <a href="{{ route('bookings.show', $booking) }}"
                               class="border border-gray-200 text-gray-700 text-xs px-4 py-2 rounded-lg font-medium hover:bg-gray-50">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $bookings->links() }}</div>
    @else
    <div class="text-center py-20 bg-white rounded-2xl shadow-sm">
        <div class="text-6xl mb-4">📋</div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">No bookings yet</h2>
        <p class="text-gray-500 mb-6">Browse our collection and book your perfect dress</p>
        <a href="{{ route('dresses.index') }}" class="bg-primary-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-primary-700">
            Browse Dresses
        </a>
    </div>
    @endif
</div>
@endsection
