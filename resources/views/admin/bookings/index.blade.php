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
                    @if($booking->user->phone)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $booking->user->phone) }}" target="_blank"
                           class="text-xs text-green-600 hover:text-green-700 flex items-center gap-1">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.116.55 4.102 1.514 5.834L0 24l6.334-1.482A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.031-1.384l-.361-.214-3.741.875.909-3.63-.236-.374A9.793 9.793 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                            {{ $booking->user->phone }}
                        </a>
                    @else
                        <div class="text-xs text-gray-500">—</div>
                    @endif
                </td>
                <td class="px-5 py-3 hidden md:table-cell">
                    <div class="flex items-center gap-2">
                        @php $dressImage = $booking->dress->primaryImage(); @endphp
                        @if($dressImage)
                            <img src="{{ $dressImage->url }}" class="w-9 h-9 rounded-lg object-cover shrink-0" alt="">
                        @else
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <span class="text-sm text-gray-600">{{ $booking->dress->name ?? '-' }}</span>
                    </div>
                </td>
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
