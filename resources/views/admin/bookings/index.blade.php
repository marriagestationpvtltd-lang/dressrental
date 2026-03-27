@extends('layouts.admin')
@section('title', 'Manage Bookings')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Manage Bookings</h1>
</div>

<!-- Filters -->
<form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user/dress..."
           class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none flex-1 min-w-40">
    <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
        <option value="">All Status</option>
        @foreach($statuses as $status)
            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-primary-700">Filter</button>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">#</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Dress</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Period</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-sm text-gray-500">#{{ $booking->id }}</td>
                <td class="px-5 py-3">
                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->user->phone }}</div>
                </td>
                <td class="px-5 py-3 text-sm text-gray-600 hidden md:table-cell">{{ $booking->dress->name ?? '-' }}</td>
                <td class="px-5 py-3 text-xs text-gray-500 hidden md:table-cell">
                    {{ $booking->bs_start_date ?? $booking->start_date->format('Y-m-d') }}<br>
                    → {{ $booking->bs_end_date ?? $booking->end_date->format('Y-m-d') }}
                </td>
                <td class="px-5 py-3 text-sm font-bold text-gray-900">₨{{ number_format($booking->total_amount) }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right">
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-12 text-gray-400">No bookings found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $bookings->links() }}</div>
</div>
@endsection
