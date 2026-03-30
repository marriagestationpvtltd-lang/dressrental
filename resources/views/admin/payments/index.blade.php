@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
        <p class="text-gray-500 text-sm mt-1">Total Revenue: <span class="font-bold text-green-600">₨{{ number_format($totalRevenue) }}</span></p>
    </div>
</div>

<!-- Filters -->
<form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-3">
    <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
        <option value="">All Status</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
    </select>
    <select name="method" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
        <option value="">All Methods</option>
        <option value="esewa" {{ request('method') == 'esewa' ? 'selected' : '' }}>eSewa</option>
        <option value="khalti" {{ request('method') == 'khalti' ? 'selected' : '' }}>Khalti</option>
        <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
    </select>
    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-primary-700">Filter</button>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">ID</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Booking</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Method</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-sm text-gray-500">
                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-primary-600 hover:underline">#{{ $payment->id }}</a>
                </td>
                <td class="px-5 py-3 text-sm text-gray-900">{{ $payment->user->name }}</td>
                <td class="px-5 py-3 text-sm text-gray-600 hidden md:table-cell">
                    <a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="text-primary-600 hover:underline">Booking #{{ $payment->booking_id }}</a>
                </td>
                <td class="px-5 py-3 text-sm font-medium uppercase text-gray-700">{{ $payment->payment_method }}</td>
                <td class="px-5 py-3 text-sm font-bold text-gray-900">₨{{ number_format($payment->amount) }}</td>
                <td class="px-5 py-3 text-xs capitalize text-gray-600">{{ $payment->payment_type }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $payment->status_badge_color }}-100 text-{{ $payment->status_badge_color }}-700">
                        {{ $payment->status }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    @if($payment->status === 'pending' && $payment->payment_method === 'cash')
                    <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" onsubmit="return confirm('Approve this offline payment?')">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg">
                            Approve
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-12 text-gray-400">No payments found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
</div>
@endsection
