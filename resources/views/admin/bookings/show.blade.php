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
        <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank"
           class="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-1.5 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Invoice
        </a>
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
                    @if($booking->user->phone)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $booking->user->phone) }}" target="_blank"
                           class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-700 mt-0.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.116.55 4.102 1.514 5.834L0 24l6.334-1.482A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.031-1.384l-.361-.214-3.741.875.909-3.63-.236-.374A9.793 9.793 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                            {{ $booking->user->phone }}
                        </a>
                    @else
                        <div class="text-sm text-gray-500">—</div>
                    @endif
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
                @if($booking->discount_type !== 'none' && $booking->discount_amount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Discount
                        @if($booking->discount_type === 'percentage')
                            ({{ $booking->discount_amount }}%)
                        @endif
                    </span>
                    <span>−₨{{ number_format($booking->discount_applied) }}</span>
                </div>
                @endif
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
            <!-- Discount -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-4"
                 x-data="{ dtype: '{{ $booking->discount_type ?? 'none' }}' }">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                    <select name="discount_type" x-model="dtype" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        <option value="none">No Discount</option>
                        <option value="fixed">Fixed Amount (₨)</option>
                        <option value="percentage">Percentage (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value</label>
                    <input type="number" name="discount_amount" value="{{ $booking->discount_amount ?? 0 }}" min="0"
                           :max="dtype === 'percentage' ? 100 : ''"
                           step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
                           placeholder="0">
                    <p class="text-xs text-gray-400 mt-1" x-show="dtype === 'percentage'">Enter a value between 0 and 100.</p>
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
                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-sm font-medium capitalize text-primary-600 hover:underline">{{ $payment->payment_type }}</a>
                    <div class="text-xs text-gray-500">{{ strtoupper($payment->payment_method) }} · {{ $payment->transaction_id }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold text-sm">₨{{ number_format($payment->amount) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $payment->status_badge_color }}-100 text-{{ $payment->status_badge_color }}-700">{{ $payment->status }}</span>
                    @if($payment->status === 'pending' && $payment->payment_method === 'cash')
                        <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" onsubmit="return confirm('Approve this cash payment?')">
                            @csrf
                            <button type="submit" class="text-xs text-green-600 hover:text-green-800 font-medium">Approve</button>
                        </form>
                    @endif
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
