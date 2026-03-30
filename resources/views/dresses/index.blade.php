@extends('layouts.app')

@section('title', 'Browse Dresses')

@section('content')

<!-- Page Header -->
<div class="gradient-hero text-white">
    <div class="max-w-7xl mx-auto px-4 py-10 md:py-14">
        <div class="flex items-center gap-3 text-sm text-violet-300 mb-3">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-white font-medium">Browse Dresses</span>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold">Browse Our Collection</h1>
        <p class="text-violet-200 mt-2 text-sm md:text-base">Discover premium dresses for every occasion</p>
    </div>
    <div class="h-6 overflow-hidden">
        <svg viewBox="0 0 1440 24" class="w-full" preserveAspectRatio="none" fill="#f9fafb">
            <path d="M0,24 C360,0 1080,0 1440,24 L1440,24 L0,24 Z"/>
        </svg>
    </div>
</div>

<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-6">

            <!-- ── Filters Sidebar ── -->
            <aside class="md:w-64 shrink-0">
                <div class="bg-white rounded-2xl shadow-card border border-violet-100 sticky top-20 overflow-hidden">
                    <!-- Sidebar Header -->
                    <div class="bg-gradient-to-r from-primary-600 to-rose-500 px-5 py-4">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filter Dresses
                        </h3>
                    </div>

                    <form method="GET" action="{{ route('dresses.index') }}" class="p-5 space-y-5">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search dresses..."
                                       class="w-full border border-violet-200 bg-violet-50/50 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-400 outline-none transition-colors">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px bg-gradient-to-r from-transparent via-violet-100 to-transparent"></div>

                        <!-- Category -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Category</label>
                            <select name="category" class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-colors">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->dresses_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Divider -->
                        <div class="h-px bg-gradient-to-r from-transparent via-violet-100 to-transparent"></div>

                        <!-- Size -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Size</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($sizes as $size)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="size" value="{{ $size }}" class="hidden peer" {{ request('size') == $size ? 'checked' : '' }}>
                                        <div class="peer-checked:bg-primary-600 peer-checked:text-white peer-checked:border-primary-600 bg-gray-50 border border-gray-200 rounded-lg text-center py-1.5 text-xs font-semibold hover:border-primary-400 hover:text-primary-600 transition-all">
                                            {{ $size }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px bg-gradient-to-r from-transparent via-violet-100 to-transparent"></div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Price per Day (₨)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">₨</span>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                           class="w-full border border-violet-200 bg-violet-50/50 rounded-xl pl-7 pr-2 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-colors">
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">₨</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                           class="w-full border border-violet-200 bg-violet-50/50 rounded-xl pl-7 pr-2 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px bg-gradient-to-r from-transparent via-violet-100 to-transparent"></div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sort By</label>
                            <select name="sort" class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-colors">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-bold hover:opacity-90 transition-opacity shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['search','category','size','min_price','max_price']))
                            <a href="{{ route('dresses.index') }}" class="flex items-center justify-center gap-1.5 text-center text-sm text-rose-500 hover:text-rose-600 font-semibold border border-rose-200 rounded-xl py-2 hover:bg-rose-50 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Clear Filters
                            </a>
                        @endif
                    </form>
                </div>
            </aside>

            <!-- ── Products Grid ── -->
            <div class="flex-1">
                <!-- Results header -->
                <div class="flex items-center justify-between mb-6 bg-white rounded-2xl px-5 py-3.5 border border-violet-100 shadow-card">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-6 gradient-bg rounded-full"></div>
                        <h1 class="text-base font-extrabold text-gray-900">
                            {{ $dresses->total() }} <span class="text-gray-400 font-medium">Dresses Available</span>
                        </h1>
                    </div>
                    @if(request()->hasAny(['search','category','size','min_price','max_price']))
                    <span class="text-xs font-semibold text-primary-600 bg-primary-50 border border-primary-200 px-3 py-1 rounded-full">
                        Filtered Results
                    </span>
                    @endif
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
                        <div class="w-24 h-24 bg-violet-50 border-2 border-violet-100 rounded-3xl flex items-center justify-center text-5xl mx-auto mb-5">👗</div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2">No Dresses Found</h3>
                        <p class="text-gray-400 mb-6 text-sm">Try adjusting your search or filter criteria</p>
                        <a href="{{ route('dresses.index') }}" class="inline-flex items-center gap-2 gradient-bg text-white px-7 py-3 rounded-xl font-bold hover:opacity-90 transition-opacity shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            View All Dresses
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
