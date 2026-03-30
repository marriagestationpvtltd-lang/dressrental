@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Page heading -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Dashboard</h1>
            <p class="text-gray-400 text-sm mt-0.5">Overview of your DressRental business</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm text-sm text-gray-500 font-medium">
            <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ now()->format('M d, Y') }}
        </div>
    </div>

    <!-- Stats Grid -->
    @php
        $adminStats = [
            ['label' => 'Total Dresses',  'value' => $stats['total_dresses'],  'sub' => $stats['available_dress'].' available',  'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16',                   'gradient' => 'gradient-bg',      'border' => 'border-violet-200', 'bg' => 'bg-violet-50'],
            ['label' => 'Total Bookings', 'value' => $stats['total_bookings'], 'sub' => $stats['active_bookings'].' active',      'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'gradient' => 'gradient-emerald',  'border' => 'border-emerald-200','bg' => 'bg-emerald-50'],
            ['label' => 'Total Users',    'value' => $stats['total_users'],    'sub' => 'registered',                             'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'gradient' => 'background: linear-gradient(135deg,#0284c7,#38bdf8)', 'border' => 'border-sky-200',    'bg' => 'bg-sky-50'],
            ['label' => 'Revenue',        'value' => '₨'.number_format($stats['total_revenue']), 'sub' => $stats['today_bookings'].' today', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'gradient' => 'gradient-gold',     'border' => 'border-amber-200',  'bg' => 'bg-amber-50'],
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($adminStats as $stat)
        <div class="bg-white rounded-2xl p-5 shadow-sm border {{ $stat['border'] }} relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute -top-4 -right-4 w-20 h-20 {{ $stat['bg'] }} rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-10 h-10 {{ $stat['gradient'] }} rounded-xl flex items-center justify-center mb-3 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $stat['icon'] }}"/></svg>
                </div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $stat['value'] }}</div>
                <div class="text-sm font-bold text-gray-600 mt-0.5">{{ $stat['label'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $stat['sub'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pending Alert -->
    @if($stats['pending_bookings'] > 0)
    <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-4 flex items-center gap-4">
        <div class="w-10 h-10 gradient-gold rounded-xl flex items-center justify-center text-xl shrink-0">⚠️</div>
        <div class="flex-1">
            <div class="font-bold text-amber-800">{{ $stats['pending_bookings'] }} bookings awaiting payment</div>
            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="text-sm text-amber-600 hover:text-amber-700 font-semibold">View pending bookings →</a>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl shadow-sm border border-violet-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-violet-50 bg-gradient-to-r from-violet-50 to-pink-50">
                <h2 class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 gradient-bg rounded-full inline-block"></span>
                    Recent Bookings
                </h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 border border-primary-200 px-2.5 py-1 rounded-full">View All →</a>
            </div>
            <div class="divide-y divide-violet-50">
                @foreach($recentBookings as $booking)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-violet-50/50 transition-colors">
                    <div>
                        <div class="text-sm font-bold text-gray-900">#{{ $booking->id }} · {{ $booking->user->name }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $booking->dress->name ?? '' }} · {{ $booking->total_days }}d</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-extrabold text-gray-900">₨{{ number_format($booking->total_amount) }}</div>
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold mt-0.5 bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700 border border-{{ $booking->status_badge_color }}-200">{{ $booking->status }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-amber-50 bg-gradient-to-r from-amber-50 to-yellow-50">
                <h2 class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 gradient-gold rounded-full inline-block"></span>
                    Recent Payments
                </h2>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">View All →</a>
            </div>
            <div class="divide-y divide-amber-50">
                @foreach($recentPayments as $payment)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-amber-50/50 transition-colors">
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $payment->user->name }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ strtoupper($payment->payment_method) }} · {{ $payment->payment_type }}</div>
                    </div>
                    <div class="text-sm font-extrabold text-emerald-600">₨{{ number_format($payment->amount) }}</div>
                </div>
                @endforeach
                @if($recentPayments->isEmpty())
                <div class="px-5 py-8 text-center text-gray-400 text-sm">No payments yet</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @php
        $quickActions = [
            ['href' => route('admin.dresses.create'),   'label' => 'Add Dress',    'emoji' => '➕', 'primary' => true],
            ['href' => route('admin.categories.create'), 'label' => 'Add Category', 'emoji' => '🏷️', 'primary' => false],
            ['href' => route('admin.bookings.index'),   'label' => 'All Bookings', 'emoji' => '📋', 'primary' => false],
            ['href' => route('admin.payments.index'),   'label' => 'Payments',     'emoji' => '💳', 'primary' => false],
        ];
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($quickActions as $action)
        <a href="{{ $action['href'] }}"
           class="{{ $action['primary'] ? 'gradient-bg text-white shadow-glow-primary hover:opacity-90' : 'bg-white border-2 border-violet-100 hover:border-primary-300 hover:shadow-md text-gray-700' }} rounded-2xl p-5 text-center transition-all group">
            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform inline-block">{{ $action['emoji'] }}</div>
            <div class="font-bold text-sm {{ $action['primary'] ? '' : 'text-gray-700' }}">{{ $action['label'] }}</div>
        </a>
        @endforeach
    </div>

</div>
@endsection
