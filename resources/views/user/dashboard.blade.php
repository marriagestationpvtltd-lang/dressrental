@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ $user->name }}! 👋</h1>
        <p class="text-gray-500 mt-1">Manage your bookings and profile</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Bookings', 'value' => $stats['total'], 'color' => 'blue', 'emoji' => '📋'],
            ['label' => 'Active Rentals', 'value' => $stats['active'], 'color' => 'green', 'emoji' => '👗'],
            ['label' => 'Completed', 'value' => $stats['completed'], 'color' => 'gray', 'emoji' => '✅'],
            ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'yellow', 'emoji' => '⏳'],
        ] as $stat)
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="text-2xl mb-1">{{ $stat['emoji'] }}</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</div>
            <div class="text-sm text-gray-500">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="font-bold text-gray-900 text-lg">Recent Bookings</h2>
            <a href="{{ route('bookings.index') }}" class="text-sm text-primary-600 hover:underline font-medium">View All</a>
        </div>

        @if($recentBookings->count())
        <div class="divide-y divide-gray-50">
            @foreach($recentBookings as $booking)
            <div class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors">
                <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                    @if($booking->dress && $booking->dress->primaryImage())
                        <img src="{{ $booking->dress->primaryImage()->url }}" class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl">👗</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-gray-900 truncate">{{ $booking->dress->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }}
                        → {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="font-bold text-gray-900">₨{{ number_format($booking->advance_amount) }}</div>
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <a href="{{ route('bookings.show', $booking) }}" class="text-primary-600 hover:text-primary-700 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-5xl mb-3">👗</div>
            <p class="text-gray-500 mb-4">No bookings yet</p>
            <a href="{{ route('dresses.index') }}" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-primary-700">
                Browse Dresses
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
