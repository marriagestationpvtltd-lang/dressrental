@extends('layouts.admin')
@section('title', $user->name)

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-4 mb-4">
            <img src="{{ $user->profile_photo_url }}" class="w-16 h-16 rounded-full object-cover" alt="">
            <div>
                <h2 class="font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                @if($user->phone)<p class="text-gray-500 text-sm">{{ $user->phone }}</p>@endif
            </div>
        </div>
        @if($user->address)
        <div class="text-sm text-gray-600"><span class="font-medium">Address:</span> {{ $user->address }}</div>
        @endif
        <div class="text-sm text-gray-500 mt-2">Joined {{ $user->created_at->format('M d, Y') }}</div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Booking History ({{ $user->bookings->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($user->bookings as $booking)
            <div class="flex items-center justify-between px-5 py-3">
                <div>
                    <div class="text-sm font-medium text-gray-900">#{{ $booking->id }} - {{ $booking->dress->name ?? '' }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->start_date->format('M d') }} → {{ $booking->end_date->format('M d, Y') }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold">₨{{ number_format($booking->total_amount) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $booking->status_badge_color }}-100 text-{{ $booking->status_badge_color }}-700">{{ $booking->status }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400 text-sm">No bookings</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
