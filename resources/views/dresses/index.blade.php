@extends('layouts.app')

@section('title', 'Browse Dresses')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-6">

        <!-- Filters Sidebar -->
        <aside class="md:w-64 shrink-0">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 sticky top-20">
                <h3 class="font-bold text-gray-900 mb-4">Filters</h3>
                <form method="GET" action="{{ route('dresses.index') }}">
                    <!-- Search -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search dresses..."
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->dresses_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Size -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($sizes as $size)
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="{{ $size }}" class="hidden peer" {{ request('size') == $size ? 'checked' : '' }}>
                                    <div class="peer-checked:bg-primary-600 peer-checked:text-white bg-gray-50 border border-gray-200 rounded-lg text-center py-1.5 text-xs font-medium hover:border-primary-400 transition-colors">
                                        {{ $size }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price per Day (₨)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select name="sort" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-xl font-semibold hover:bg-primary-700 transition-colors">
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search','category','size','min_price','max_price']))
                        <a href="{{ route('dresses.index') }}" class="block text-center mt-2 text-sm text-gray-500 hover:text-red-500">Clear filters</a>
                    @endif
                </form>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-gray-900">
                    {{ $dresses->total() }} Dresses Available
                </h1>
            </div>

            @if($dresses->count())
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($dresses as $dress)
                        @include('components.dress-card', ['dress' => $dress])
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $dresses->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">👗</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No dresses found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your filters</p>
                    <a href="{{ route('dresses.index') }}" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-primary-700">
                        View All Dresses
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
