@extends('layouts.app')

@section('title', 'New Arrivals')

@section('content')

<!-- Page Header -->
<div class="gradient-hero text-white">
    <div class="max-w-7xl mx-auto px-4 py-10 md:py-14">
        <div class="flex items-center gap-3 text-sm text-violet-300 mb-3">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('dresses.index') }}" class="hover:text-white transition-colors">Dresses</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-white font-medium">New Arrivals</span>
        </div>
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-4xl">✨</span>
                    <h1 class="text-3xl md:text-4xl font-extrabold">New Arrivals</h1>
                </div>
                <p class="text-violet-200 mt-2 text-sm md:text-base">Fresh additions to our exclusive collection</p>
            </div>
            <div class="mt-2">
                <x-share-button
                    :url="route('dresses.new-arrivals')"
                    :title="'New Arrivals — ' . config('app.name')"
                />
            </div>
        </div>
    </div>
    <div class="h-6 overflow-hidden">
        <svg viewBox="0 0 1440 24" class="w-full" preserveAspectRatio="none" fill="#f9fafb">
            <path d="M0,24 C360,0 1080,0 1440,24 L1440,24 L0,24 Z"/>
        </svg>
    </div>
</div>

<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Results bar -->
        <div class="flex items-center justify-between mb-6 bg-white rounded-2xl px-5 py-3.5 border border-violet-100 shadow-card">
            <div class="flex items-center gap-2">
                <div class="w-2 h-6 bg-gradient-to-b from-amber-300 to-amber-500 rounded-full"></div>
                <h2 class="text-base font-extrabold text-gray-900">
                    {{ $dresses->total() }} <span class="text-gray-400 font-medium">New Arrivals</span>
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-100 border border-amber-200 px-3 py-1 rounded-full">
                    ✨ Just Added
                </span>
                <form method="GET" action="{{ route('dresses.new-arrivals') }}">
                    <select name="sort" onchange="this.form.submit()"
                            class="border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-colors">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </form>
            </div>
        </div>

        @if($dresses->count())
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5">
                @foreach($dresses as $dress)
                    @include('components.dress-card', ['dress' => $dress])
                @endforeach
            </div>

            <div class="mt-8">
                {{ $dresses->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-violet-100 shadow-card">
                <div class="w-24 h-24 bg-amber-50 border-2 border-amber-100 rounded-3xl flex items-center justify-center text-5xl mx-auto mb-5">✨</div>
                <h3 class="text-xl font-extrabold text-gray-900 mb-2">No New Arrivals Yet</h3>
                <p class="text-gray-400 mb-6 text-sm">Check back soon for our latest additions</p>
                <a href="{{ route('dresses.index') }}" class="inline-flex items-center gap-2 gradient-bg text-white px-7 py-3 rounded-xl font-bold hover:opacity-90 transition-opacity shadow-sm">
                    Browse All Dresses
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
