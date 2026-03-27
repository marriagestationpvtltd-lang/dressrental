@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <div class="text-sm text-gray-500">{{ now()->format('M d, Y') }}</div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Dresses', 'value' => $stats['total_dresses'], 'sub' => $stats['available_dress'] . ' available', 'color' => 'purple', 'icon' => '👗'],
            ['label' => 'Total Bookings', 'value' => $stats['total_bookings'], 'sub' => $stats['active_bookings'] . ' active', 'color' => 'blue', 'icon' => '📋'],
            ['label' => 'Total Users', 'value' => $stats['total_users'], 'sub' => 'registered', 'color' => 'green', 'icon' => '👥'],
            ['label' => 'Revenue', 'value' => '₨' . number_format($stats['total_revenue']), 'sub' => $stats['today_bookings'] . ' today', 'color' => 'yellow', 'icon' => '💰'],
        ] as $stat)
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="text-2xl mb-2">{{ $stat['icon'] }}</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</div>
            <div class="text-sm font-medium text-gray-700">{{ $stat['label'] }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ $stat['sub'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Pending Bookings Alert -->
    @if($stats['pending_bookings'] > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 flex items-center gap-3">
        <span class="text-2xl">⚠️</span>
        <div>
            <div class="font-semibold text-yellow-800">{{ $stats['pending_bookings'] }} bookings awaiting payment</div>
            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="text-sm text-yellow-600 hover:underline">View pending bookings →</a>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Recent Bookings</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-primary-600 hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recentBookings as $booking)
                <div class="flex items-center justify-between px-5 py-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">#{{ $booking->id }} - {{ $booking->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->dress->name ?? '' }} · {{ $booking->total_days }}d</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold">₨{{ number_format($booking->total_amount) }}</div>
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700">{{ $booking->status }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Recent Payments</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-sm text-primary-600 hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recentPayments as $payment)
                <div class="flex items-center justify-between px-5 py-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ strtoupper($payment->payment_method) }} · {{ $payment->payment_type }}</div>
                    </div>
                    <div class="text-sm font-bold text-green-600">₨{{ number_format($payment->amount) }}</div>
                </div>
                @endforeach
                @if($recentPayments->isEmpty())
                <div class="px-5 py-8 text-center text-gray-400 text-sm">No payments yet</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.dresses.create') }}" class="bg-primary-600 text-white rounded-2xl p-4 text-center hover:bg-primary-700 transition-colors">
            <div class="text-2xl mb-1">➕</div>
            <div class="font-medium text-sm">Add Dress</div>
        </a>
        <a href="{{ route('admin.categories.create') }}" class="bg-white border border-gray-200 rounded-2xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl mb-1">🏷️</div>
            <div class="font-medium text-sm text-gray-700">Add Category</div>
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="bg-white border border-gray-200 rounded-2xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl mb-1">📋</div>
            <div class="font-medium text-sm text-gray-700">All Bookings</div>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="bg-white border border-gray-200 rounded-2xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-2xl mb-1">💳</div>
            <div class="font-medium text-sm text-gray-700">Payments</div>
        </a>
    </div>
</div>
@endsection
