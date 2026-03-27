@extends('layouts.app')
@section('title', 'Payment Failed')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-3">Payment Failed</h1>
    <p class="text-gray-500 mb-8">Something went wrong with your payment. Your booking is still saved.</p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 text-left">
        @foreach($errors->all() as $error)
            <p class="text-red-700 text-sm">{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('payment.initiate', $booking) }}" class="bg-primary-600 text-white font-bold px-8 py-3 rounded-2xl hover:bg-primary-700">
            Try Again
        </a>
        <a href="{{ route('bookings.index') }}" class="border border-gray-200 text-gray-700 font-medium px-8 py-3 rounded-2xl hover:bg-gray-50">
            My Bookings
        </a>
    </div>
</div>
@endsection
