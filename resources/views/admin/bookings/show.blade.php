@extends('layouts.admin')
@section('title', 'Booking #' . $booking->id)

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
        <span class="ml-auto px-3 py-1 rounded-full text-sm font-medium bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Customer</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $booking->user->profile_photo_url }}" class="w-12 h-12 rounded-full object-cover" alt="">
                <div>
                    <div class="font-medium text-gray-900">{{ $booking->user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->user->phone }}</div>
                </div>
            </div>
        </div>

        <!-- Dress Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Dress</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100">
                    @if($booking->dress->primaryImage())
                        <img src="{{ $booking->dress->primaryImage()->url }}" class="w-full h-full object-cover" alt="">
                    @endif
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ $booking->dress->name }}</div>
                    <div class="text-sm text-gray-500">Size: {{ $booking->dress->size }}</div>
                </div>
            </div>
        </div>

        <!-- Rental Period -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Rental Period</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Start</span><span class="font-medium">{{ $booking->bs_start_date ?? '' }} ({{ $booking->start_date->format('M d, Y') }})</span></div>
                <div class="flex justify-between"><span class="text-gray-500">End</span><span class="font-medium">{{ $booking->bs_end_date ?? '' }} ({{ $booking->end_date->format('M d, Y') }})</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Days</span><span class="font-bold">{{ $booking->total_days }}</span></div>
            </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Payment</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Rental</span><span>₨{{ number_format($booking->rental_amount) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Deposit</span><span>₨{{ number_format($booking->deposit_amount) }}</span></div>
                <div class="flex justify-between font-bold border-t border-gray-100 pt-2"><span>Total</span><span>₨{{ number_format($booking->total_amount) }}</span></div>
                <div class="flex justify-between text-primary-600"><span>Advance</span><span>₨{{ number_format($booking->advance_amount) }}</span></div>
                @if($booking->fine_amount > 0)
                <div class="flex justify-between text-red-600"><span>Fine</span><span>₨{{ number_format($booking->fine_amount) }}</span></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Update Status -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mt-6">
        <h3 class="font-semibold text-gray-900 mb-4">Update Booking</h3>
        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        @foreach(['pending','paid','active','returned','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $booking->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fine Amount (₨)</label>
                    <input type="number" name="fine_amount" value="{{ $booking->fine_amount }}" min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <input type="text" name="notes" value="{{ $booking->notes }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
            </div>
            <button type="submit" class="bg-primary-600 text-white px-6 py-2.5 rounded-xl font-medium hover:bg-primary-700 transition-colors">
                Update Booking
            </button>
        </form>
    </div>

    <!-- Payments -->
    @if($booking->payments->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-6 overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Payment History</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($booking->payments as $payment)
            <div class="flex items-center justify-between px-5 py-3">
                <div>
                    <div class="text-sm font-medium capitalize">{{ $payment->payment_type }}</div>
                    <div class="text-xs text-gray-500">{{ strtoupper($payment->payment_method) }} · {{ $payment->transaction_id }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold text-sm">₨{{ number_format($payment->amount) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $payment->status_badge_color }}-100 text-{{ $payment->status_badge_color }}-700">{{ $payment->status }}</span>
                    @if($payment->status === 'completed' && $payment->payment_type === 'advance')
                        <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" onsubmit="return confirm('Refund deposit?')">
                            @csrf
                            <button type="submit" class="text-xs text-purple-600 hover:text-purple-800 font-medium">Refund Deposit</button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
