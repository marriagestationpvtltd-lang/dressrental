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
                @if($user->phone)
                <p class="text-sm mt-0.5">
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $user->phone) }}" target="_blank"
                       class="inline-flex items-center gap-1 text-green-600 hover:text-green-700">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.116.55 4.102 1.514 5.834L0 24l6.334-1.482A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.031-1.384l-.361-.214-3.741.875.909-3.63-.236-.374A9.793 9.793 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                        {{ $user->phone }}
                    </a>
                </p>
                @endif
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
