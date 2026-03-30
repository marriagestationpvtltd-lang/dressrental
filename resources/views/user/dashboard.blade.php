@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')

<!-- Dashboard Header -->
<div class="gradient-hero text-white">
    <div class="max-w-7xl mx-auto px-4 py-10 md:py-14">
        <div class="flex items-center gap-4">
            <div class="relative">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                     class="w-16 h-16 rounded-2xl object-cover border-3 border-white/40 shadow-lg">
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-400 border-2 border-white rounded-full"></div>
            </div>
            <div>
                <p class="text-violet-300 text-sm font-semibold">Welcome back 👋</p>
                <h1 class="text-2xl md:text-3xl font-extrabold">{{ $user->name }}</h1>
                <p class="text-violet-300 text-sm mt-0.5">{{ $user->email }}</p>
            </div>
        </div>
    </div>
    <div class="h-6 overflow-hidden">
        <svg viewBox="0 0 1440 24" class="w-full" preserveAspectRatio="none" fill="#f9fafb">
            <path d="M0,24 C360,0 1080,0 1440,24 L1440,24 L0,24 Z"/>
        </svg>
    </div>
</div>

<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Stats Grid -->
        @php
            $statCards = [
                ['label' => 'Total Bookings', 'value' => $stats['total'],     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'gradient' => 'gradient-bg',      'border' => 'border-violet-200', 'bg' => 'bg-violet-50'],
                ['label' => 'Active Rentals', 'value' => $stats['active'],    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16',                                                                                                   'gradient' => 'gradient-emerald',  'border' => 'border-emerald-200','bg' => 'bg-emerald-50'],
                ['label' => 'Completed',      'value' => $stats['completed'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                      'gradient' => 'gradient-gold',     'border' => 'border-amber-200',  'bg' => 'bg-amber-50'],
                ['label' => 'Pending',        'value' => $stats['pending'],   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                        'gradient' => 'gradient-rose',     'border' => 'border-rose-200',   'bg' => 'bg-rose-50'],
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach($statCards as $card)
            <div class="bg-white rounded-2xl p-5 shadow-card border {{ $card['border'] }} relative overflow-hidden group hover:shadow-card-hover transition-all">
                <!-- Background decoration -->
                <div class="absolute -top-4 -right-4 w-20 h-20 {{ $card['bg'] }} rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="w-10 h-10 {{ $card['gradient'] }} rounded-xl flex items-center justify-center mb-3 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-0.5">{{ $card['value'] }}</div>
                    <div class="text-xs md:text-sm text-gray-500 font-medium">{{ $card['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('dresses.index') }}"
               class="bg-white rounded-2xl p-4 shadow-card border border-violet-100 hover:border-primary-300 hover:shadow-card-hover transition-all flex items-center gap-3 group">
                <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <div>
                    <div class="font-bold text-gray-900 text-sm">Browse Dresses</div>
                    <div class="text-xs text-gray-400">Find your next look</div>
                </div>
            </a>
            <a href="{{ route('bookings.index') }}"
               class="bg-white rounded-2xl p-4 shadow-card border border-emerald-100 hover:border-emerald-300 hover:shadow-card-hover transition-all flex items-center gap-3 group">
                <div class="w-10 h-10 gradient-emerald rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <div class="font-bold text-gray-900 text-sm">My Bookings</div>
                    <div class="text-xs text-gray-400">View all bookings</div>
                </div>
            </a>
            <a href="{{ route('profile') }}"
               class="bg-white rounded-2xl p-4 shadow-card border border-amber-100 hover:border-amber-300 hover:shadow-card-hover transition-all flex items-center gap-3 group col-span-2 md:col-span-1">
                <div class="w-10 h-10 gradient-gold rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <div class="font-bold text-gray-900 text-sm">My Profile</div>
                    <div class="text-xs text-gray-400">Update your info</div>
                </div>
            </a>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-3xl shadow-card border border-violet-100 overflow-hidden">
            <!-- Section header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-violet-50 bg-gradient-to-r from-violet-50 to-pink-50">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 gradient-bg rounded flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    </div>
                    <h2 class="font-extrabold text-gray-900">Recent Bookings</h2>
                </div>
                <a href="{{ route('bookings.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 border border-primary-200 px-3 py-1.5 rounded-full transition-colors">View All →</a>
            </div>

            @if($recentBookings->count())
            <div class="divide-y divide-violet-50">
                @foreach($recentBookings as $booking)
                <div class="flex items-center gap-4 p-4 hover:bg-violet-50/50 transition-colors">
                    <!-- Dress image -->
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gradient-to-br from-violet-100 to-pink-100 border border-violet-100 shrink-0">
                        @if($booking->dress && $booking->dress->primaryImage())
                            <img src="{{ asset('storage/' . $booking->dress->primaryImage()->image_path) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xl">👗</div>
                        @endif
                    </div>
                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-gray-900 truncate text-sm">{{ $booking->dress->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }}
                            → {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}
                        </div>
                    </div>
                    <!-- Right side -->
                    <div class="text-right shrink-0">
                        <div class="font-extrabold text-gray-900 text-sm">₨{{ number_format($booking->advance_amount) }}</div>
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold mt-0.5 bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700 border border-{{ $booking->status_badge_color }}-200">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <a href="{{ route('bookings.show', $booking) }}" class="w-8 h-8 bg-primary-50 border border-primary-200 rounded-lg flex items-center justify-center text-primary-600 hover:bg-primary-100 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-violet-50 border-2 border-violet-100 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-4">👗</div>
                <h3 class="font-extrabold text-gray-900 mb-1.5">No Bookings Yet</h3>
                <p class="text-gray-400 text-sm mb-6">Start renting and your bookings will appear here</p>
                <a href="{{ route('dresses.index') }}" class="inline-flex items-center gap-2 gradient-bg text-white px-7 py-3 rounded-xl font-bold hover:opacity-90 shadow-sm transition-opacity">
                    Browse Dresses
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
