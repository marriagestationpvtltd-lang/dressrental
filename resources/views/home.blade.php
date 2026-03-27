@extends('layouts.app')

@section('title', 'Premium Dress Rentals in Nepal')

@section('content')
<!-- Hero Section -->
<section class="gradient-bg text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 md:py-32">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 text-sm font-medium mb-6">
                <span>🎉</span>
                <span>Nepal's #1 Dress Rental Platform</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Rent · Wear · Return
                <span class="block text-yellow-300">Look Stunning Always</span>
            </h1>
            <p class="text-lg md:text-xl text-purple-100 mb-8">
                Browse thousands of premium dresses. Book with Nepali calendar. Pay with eSewa & Khalti.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dresses.index') }}" class="bg-white text-primary-700 font-bold px-8 py-4 rounded-2xl hover:bg-yellow-50 transition-all shadow-lg text-lg">
                    Browse Dresses →
                </a>
                @guest
                    <a href="{{ route('register') }}" class="bg-white/20 border-2 border-white text-white font-bold px-8 py-4 rounded-2xl hover:bg-white/30 transition-all text-lg">
                        Get Started Free
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-3 md:grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-2xl md:text-3xl font-bold text-primary-600">500+</div>
                <div class="text-sm text-gray-500">Premium Dresses</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-bold text-primary-600">2000+</div>
                <div class="text-sm text-gray-500">Happy Customers</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-bold text-primary-600">₨50+</div>
                <div class="text-sm text-gray-500">Starting Price/Day</div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
@if($categories->count())
<section class="py-12 px-4 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Shop by Category</h2>
            <p class="text-gray-500 mt-1">Find the perfect dress for every occasion</p>
        </div>
        <a href="{{ route('dresses.index') }}" class="text-primary-600 font-medium hover:underline hidden md:block">View All →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach($categories as $cat)
        <a href="{{ route('dresses.index', ['category' => $cat->slug]) }}"
           class="bg-white rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition-all border border-gray-100 card-hover group">
            <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center mx-auto mb-3 text-xl group-hover:scale-110 transition-transform">
                {{ $cat->icon ?: '👗' }}
            </div>
            <div class="font-semibold text-gray-800 text-sm">{{ $cat->name }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $cat->dresses_count }} dresses</div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- Featured Dresses -->
@if($featuredDresses->count())
<section class="py-12 px-4 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Featured Dresses</h2>
            <p class="text-gray-500 mt-1">Hand-picked premium selections</p>
        </div>
        <a href="{{ route('dresses.index') }}" class="text-primary-600 font-medium hover:underline">View All →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($featuredDresses as $dress)
            @include('components.dress-card', ['dress' => $dress])
        @endforeach
    </div>
</section>
@endif

<!-- How it works -->
<section class="py-16 bg-gradient-to-br from-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">How It Works</h2>
            <p class="text-gray-500 mt-2">Simple 4-step process</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['emoji' => '👀', 'title' => 'Browse', 'desc' => 'Explore hundreds of premium dresses'],
                ['emoji' => '📅', 'title' => 'Book', 'desc' => 'Select dates using Nepali calendar'],
                ['emoji' => '💳', 'title' => 'Pay', 'desc' => 'Pay via eSewa or Khalti'],
                ['emoji' => '👗', 'title' => 'Wear & Return', 'desc' => 'Enjoy & return on time'],
            ] as $i => $step)
            <div class="text-center">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center text-3xl mx-auto mb-4">
                    {{ $step['emoji'] }}
                </div>
                <div class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-1">Step {{ $i+1 }}</div>
                <h3 class="font-bold text-gray-900 mb-1">{{ $step['title'] }}</h3>
                <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- New Arrivals -->
@if($newArrivals->count())
<section class="py-12 px-4 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">New Arrivals</h2>
            <p class="text-gray-500 mt-1">Fresh additions to our collection</p>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        @foreach($newArrivals as $dress)
            @include('components.dress-card', ['dress' => $dress])
        @endforeach
    </div>
</section>
@endif

<!-- CTA Banner -->
<section class="mx-4 mb-12 rounded-3xl gradient-bg text-white overflow-hidden">
    <div class="px-8 py-12 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Ready to Look Stunning?</h2>
        <p class="text-purple-100 mb-8 max-w-md mx-auto">Join thousands of customers who rent premium dresses for every occasion.</p>
        <a href="{{ route('dresses.index') }}" class="inline-block bg-white text-primary-700 font-bold px-8 py-4 rounded-2xl hover:bg-yellow-50 transition-all shadow-lg">
            Explore Dresses
        </a>
    </div>
</section>

@endsection
