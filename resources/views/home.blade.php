@extends('layouts.app')

@section('title', 'Premium Dress Rentals in Nepal')

@push('styles')
<style>
/* ── Featured Dresses Paged Slider ── */
.fs-wrapper {
    touch-action: pan-y;
    cursor: grab;
    user-select: none;
    -webkit-user-select: none;
    position: relative;
}
.fs-wrapper.is-dragging { cursor: grabbing; }

.fs-viewport { overflow: hidden; }

.fs-track {
    display: flex;
    gap: 1rem;
    transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}
.fs-track.fs-no-transition { transition: none; }

/* card: ~75 vw on mobile, capped for larger screens */
.fs-card {
    flex-shrink: 0;
    width: clamp(240px, 75vw, 320px);
    max-height: 80vh;
    overflow: hidden;
}

/* ── Hero Banner Slider ── */
.hero-slider {
    position: relative;
}
.hero-slider-item {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease, transform 0.7s cubic-bezier(0.25,0.46,0.45,0.94);
    pointer-events: none;
}
.hero-slider-item.active {
    opacity: 1;
    pointer-events: auto;
    z-index: 1;
}
/* slide animation: items coming in from right */
.hero-slider[data-animation="slide"] .hero-slider-item {
    transform: translateX(100%);
    opacity: 1;
}
.hero-slider[data-animation="slide"] .hero-slider-item.active {
    transform: translateX(0);
}
.hero-slider[data-animation="slide"] .hero-slider-item.leaving {
    transform: translateX(-100%);
    z-index: 0;
}
/* zoom animation */
.hero-slider[data-animation="zoom"] .hero-slider-item {
    transform: scale(1.07);
    opacity: 0;
}
.hero-slider[data-animation="zoom"] .hero-slider-item.active {
    opacity: 1;
    transform: scale(1);
}

/* ── Footer Dress Slider ── */
.footer-slider-track {
    display: flex;
    gap: 0.75rem;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}
</style>
@endpush

@section('content')

<!-- ═══════════════════ HERO ═══════════════════ -->
<section class="relative gradient-hero text-white overflow-hidden">
    <!-- Decorative blobs -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-16 -right-16 w-80 h-80 bg-rose-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-700/20 rounded-full blur-3xl pointer-events-none"></div>

    @if($heroBanners->count())
    {{-- ── Dynamic layout when banners are present ── --}}
    <div class="relative max-w-7xl mx-auto px-4 py-14 md:py-20">
        <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">

            {{-- Text content --}}
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 text-sm font-semibold mb-8 shadow-lg">
                    <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                    Nepal's #1 Dress Rental Platform
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl xl:text-6xl font-extrabold mb-6 leading-[1.1] tracking-tight">
                    Rent · Wear · Return
                    <span class="block mt-2 text-amber-300 drop-shadow-lg">Look Stunning Always</span>
                </h1>
                <p class="text-base md:text-lg text-violet-200 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Browse premium dresses. Book with the Nepali calendar. Pay with eSewa &amp; Khalti — seamlessly.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('dresses.index') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white text-primary-700 font-extrabold px-9 py-4 rounded-2xl hover:bg-amber-50 transition-all shadow-xl text-base md:text-lg">
                        Browse Dresses
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    @guest
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 bg-white/15 border-2 border-white/50 text-white font-bold px-9 py-4 rounded-2xl hover:bg-white/25 transition-all text-base md:text-lg backdrop-blur-sm">
                            Get Started Free
                        </a>
                    @endguest
                </div>
            </div>

            {{-- Media slider --}}
            <div class="flex-1 w-full max-w-xl lg:max-w-none"
                 x-data="heroSlider({{ $heroBanners->count() }}, {{ (int) setting('hero_slider_interval', 5000) }}, '{{ setting('hero_slider_animation', 'slide') }}')"
                 x-init="init()"
                 @mouseenter="pause()" @mouseleave="resume()">

                <div class="hero-slider relative rounded-2xl overflow-hidden shadow-2xl"
                     style="aspect-ratio: 16/9;"
                     data-animation="{{ setting('hero_slider_animation', 'slide') }}">

                    @foreach($heroBanners as $i => $banner)
                    <div class="hero-slider-item w-full h-full {{ $i === 0 ? 'active' : '' }}"
                         :class="{ 'active': current === {{ $i }}, 'leaving': leaving === {{ $i }} }">

                        @if($banner->media_type === 'youtube')
                            <iframe
                                src="{{ $banner->youtube_embed_url }}"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                title="{{ $banner->title ?? 'Banner video' }}"
                                loading="lazy">
                            </iframe>
                        @else
                            <img src="{{ $banner->image_url }}"
                                 alt="{{ $banner->title ?? 'Banner' }}"
                                 class="w-full h-full object-cover"
                                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                        @endif
                    </div>
                    @endforeach

                    {{-- Prev / Next controls --}}
                    @if($heroBanners->count() > 1)
                    <button @click="prev()"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center text-white transition-colors z-10"
                            aria-label="Previous banner">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center text-white transition-colors z-10"
                            aria-label="Next banner">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    {{-- Dot indicators --}}
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                        @foreach($heroBanners as $i => $banner)
                        <button @click="goTo({{ $i }})"
                                :class="current === {{ $i }} ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                                class="h-2 rounded-full transition-all duration-300"
                                aria-label="Go to banner {{ $i + 1 }}">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- ── Static hero (no banners configured) ── --}}
    <div class="relative max-w-7xl mx-auto px-4 py-24 md:py-36">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 text-sm font-semibold mb-8 shadow-lg">
                <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                Nepal's #1 Dress Rental Platform
            </div>
            <!-- Headline -->
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold mb-6 leading-[1.1] tracking-tight">
                Rent · Wear · Return
                <span class="block mt-2 text-amber-300 drop-shadow-lg">Look Stunning Always</span>
            </h1>
            <!-- Sub-heading -->
            <p class="text-lg md:text-xl text-violet-200 mb-10 max-w-xl mx-auto leading-relaxed">
                Browse premium dresses. Book with the Nepali calendar. Pay with eSewa &amp; Khalti — seamlessly.
            </p>
            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dresses.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-primary-700 font-extrabold px-9 py-4 rounded-2xl hover:bg-amber-50 transition-all shadow-xl text-base md:text-lg">
                    Browse Dresses
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @guest
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white/15 border-2 border-white/50 text-white font-bold px-9 py-4 rounded-2xl hover:bg-white/25 transition-all text-base md:text-lg backdrop-blur-sm">
                        Get Started Free
                    </a>
                @endguest
            </div>
        </div>
    </div>
    @endif

    <!-- Wave divider -->
    <div class="relative h-8 md:h-12 overflow-hidden">
        <svg viewBox="0 0 1440 48" class="absolute bottom-0 w-full" preserveAspectRatio="none" fill="#f9fafb">
            <path d="M0,48 C360,0 1080,0 1440,48 L1440,48 L0,48 Z"/>
        </svg>
    </div>
</section>

<!-- ═══════════════════ STATS ═══════════════════ -->
<section class="bg-gray-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-3 gap-6 text-center">
            @foreach([
                ['value' => '500+', 'label' => 'Premium Dresses', 'icon' => '👗', 'color' => 'primary'],
                ['value' => '2000+', 'label' => 'Happy Customers', 'icon' => '😊', 'color' => 'rose'],
                ['value' => '₨50+', 'label' => 'Starting/Day', 'icon' => '💰', 'color' => 'amber'],
            ] as $stat)
            <div class="flex flex-col items-center gap-1">
                <div class="w-10 h-10 rounded-2xl {{ $stat['color'] === 'primary' ? 'bg-violet-100 border border-violet-200' : ($stat['color'] === 'rose' ? 'bg-rose-100 border border-rose-200' : 'bg-amber-100 border border-amber-200') }} flex items-center justify-center text-xl mb-1">
                    {{ $stat['icon'] }}
                </div>
                <div class="text-2xl md:text-3xl font-extrabold {{ $stat['color'] === 'primary' ? 'text-primary-600' : ($stat['color'] === 'rose' ? 'text-rose-500' : 'text-amber-500') }}">
                    {{ $stat['value'] }}
                </div>
                <div class="text-xs md:text-sm text-gray-500 font-medium">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ═══════════════════ CATEGORIES ═══════════════════ -->
@if($categories->count())
<section class="section-violet py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="inline-block text-xs font-bold text-primary-600 uppercase tracking-widest bg-primary-100 border border-primary-200 rounded-full px-3 py-1 mb-3">Collections</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Shop by Category</h2>
                <p class="text-gray-500 mt-1.5 text-sm">Find the perfect dress for every occasion</p>
            </div>
            <a href="{{ route('dresses.index') }}" class="hidden md:inline-flex items-center gap-1.5 text-primary-600 font-semibold text-sm hover:text-primary-700 border border-primary-200 bg-white rounded-xl px-4 py-2 shadow-sm hover:shadow transition-all">
                View All <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @php
            $catColors = ['gradient-bg','gradient-rose','gradient-gold','gradient-emerald','gradient-hero','gradient-bg'];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $i => $cat)
            <a href="{{ route('categories.show', $cat->slug) }}"
               class="bg-white rounded-2xl p-5 text-center shadow-card hover:shadow-card-hover transition-all border border-violet-100 hover:border-primary-300 card-hover group">
                <div class="w-14 h-14 {{ $catColors[$loop->index % count($catColors)] }} rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:scale-110 transition-transform shadow-sm">
                    {{ $cat->icon ?: '👗' }}
                </div>
                <div class="font-bold text-gray-800 text-sm">{{ $cat->name }}</div>
                <div class="text-xs text-gray-400 mt-1 font-medium">{{ $cat->dresses_count }} dresses</div>
                @if($cat->activeSubcategories->count())
                <div class="flex flex-wrap justify-center gap-1 mt-2">
                    @foreach($cat->activeSubcategories->take(3) as $sub)
                    <span class="text-xs bg-violet-50 text-violet-600 border border-violet-100 rounded-full px-2 py-0.5">
                        {{ $sub->icon ? $sub->icon . ' ' : '' }}{{ $sub->name }}
                    </span>
                    @endforeach
                    @if($cat->activeSubcategories->count() > 3)
                    <span class="text-xs text-gray-400">+{{ $cat->activeSubcategories->count() - 3 }} more</span>
                    @endif
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ═══════════════════ FEATURED DRESSES ═══════════════════ -->
@if($featuredDresses->count())
<section class="bg-white py-6 md:py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="inline-block text-xs font-bold text-amber-600 uppercase tracking-widest bg-amber-100 border border-amber-200 rounded-full px-3 py-1 mb-3">⭐ Hand-Picked</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Featured Dresses</h2>
                <p class="text-gray-500 mt-1.5 text-sm">Premium selections curated just for you</p>
            </div>
            <a href="{{ route('dresses.featured') }}" class="hidden md:inline-flex items-center gap-1.5 text-amber-600 font-semibold text-sm hover:text-amber-700 border border-amber-200 bg-amber-50 rounded-xl px-4 py-2 shadow-sm hover:shadow transition-all">
                View All <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <!-- Paged slider: ~75% card width, next card partially visible -->
        <div class="fs-wrapper"
             :class="{ 'is-dragging': dragging }"
             x-data="featuredSlider()"
             @mouseenter="pauseAutoplay()"
             @mouseleave="resumeAutoplay()"
             @pointerdown="startDrag($event)"
             @pointermove="onDrag($event)"
             @pointerup="endDrag($event)"
             @pointercancel="cancelDrag()"
             @click.capture="if (hasDragged) { $event.preventDefault(); $event.stopPropagation(); hasDragged = false; }">

            <div class="fs-viewport">
                <div class="fs-track"
                     x-ref="track"
                     :class="{ 'fs-no-transition': dragging }"
                     :style="`transform: translateX(${trackOffset}px)`">
                    @foreach($featuredDresses as $dress)
                        @include('components.featured-slider-card', ['dress' => $dress])
                    @endforeach
                </div>
            </div>

            @if($featuredDresses->count() > 1)
            <div class="flex justify-center items-center gap-2 mt-5">
                @foreach($featuredDresses as $dress)
                <button @click="goTo({{ $loop->index }}); resetAutoplay()"
                        :class="current === {{ $loop->index }} ? 'bg-primary-600 w-5' : 'bg-gray-300 w-2'"
                        class="h-2 rounded-full transition-all duration-300"
                        aria-label="Go to slide {{ $loop->index + 1 }}"></button>
                @endforeach
            </div>
            @endif
        </div>

        <div class="mt-5 text-center md:hidden">
            <a href="{{ route('dresses.featured') }}" class="inline-flex items-center gap-1.5 text-amber-600 font-semibold text-sm hover:text-amber-700 border border-amber-200 bg-amber-50 rounded-xl px-4 py-2 shadow-sm">
                View All Featured
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- ═══════════════════ HOW IT WORKS ═══════════════════ -->
<section class="section-mixed py-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="inline-block text-xs font-bold text-rose-600 uppercase tracking-widest bg-rose-100 border border-rose-200 rounded-full px-3 py-1 mb-3">Simple Process</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">How It Works</h2>
            <p class="text-gray-500 mt-2 text-sm">Just 4 easy steps to your perfect look</p>
        </div>
        @php
            $steps = [
                ['emoji' => '👀', 'title' => 'Browse', 'desc' => 'Explore hundreds of premium dresses in every style', 'color' => 'violet', 'bg' => 'bg-violet-100 border-violet-200', 'num' => 'bg-violet-600'],
                ['emoji' => '📅', 'title' => 'Book', 'desc' => 'Select your dates using Nepali BS calendar', 'color' => 'rose', 'bg' => 'bg-rose-100 border-rose-200', 'num' => 'bg-rose-500'],
                ['emoji' => '💳', 'title' => 'Pay', 'desc' => 'Securely pay via eSewa, Khalti or cash', 'color' => 'amber', 'bg' => 'bg-amber-100 border-amber-200', 'num' => 'bg-amber-500'],
                ['emoji' => '👗', 'title' => 'Wear & Return', 'desc' => 'Enjoy your event and return on time', 'color' => 'emerald', 'bg' => 'bg-emerald-100 border-emerald-200', 'num' => 'bg-emerald-500'],
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 relative">
            <!-- Connector line (desktop only) -->
            <div class="hidden md:block absolute top-10 left-[12.5%] right-[12.5%] h-0.5 bg-gradient-to-r from-violet-300 via-rose-300 via-amber-300 to-emerald-300 z-0"></div>
            @foreach($steps as $i => $step)
            <div class="relative z-10 text-center group">
                <div class="w-20 h-20 {{ $step['bg'] }} border-2 rounded-3xl shadow-card flex items-center justify-center text-4xl mx-auto mb-4 group-hover:scale-110 transition-transform">
                    {{ $step['emoji'] }}
                </div>
                <div class="inline-flex items-center justify-center w-6 h-6 {{ $step['num'] }} text-white text-xs font-black rounded-full mb-2 shadow-sm">{{ $i+1 }}</div>
                <h3 class="font-extrabold text-gray-900 mb-1.5 text-base">{{ $step['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ═══════════════════ CATEGORY SHOWCASE ═══════════════════ -->
@if($showcaseCategories->count())
    @php
        $showcaseBgs   = ['bg-white', 'section-violet', 'bg-gray-50'];
        $showcaseAccents = [
            ['badge' => 'text-primary-600 bg-primary-100 border-primary-200', 'btn' => 'text-primary-600 hover:text-primary-700 border-primary-200 bg-white hover:shadow'],
            ['badge' => 'text-rose-600 bg-rose-100 border-rose-200',          'btn' => 'text-rose-600 hover:text-rose-700 border-rose-200 bg-white hover:shadow'],
            ['badge' => 'text-emerald-600 bg-emerald-100 border-emerald-200', 'btn' => 'text-emerald-600 hover:text-emerald-700 border-emerald-200 bg-white hover:shadow'],
        ];
    @endphp
    @foreach($showcaseCategories as $i => $showcaseCat)
    @php $accent = $showcaseAccents[$i % count($showcaseAccents)]; @endphp
    <section class="{{ $showcaseBgs[$i % count($showcaseBgs)] }} py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="inline-block text-xs font-bold uppercase tracking-widest border rounded-full px-3 py-1 mb-3 {{ $accent['badge'] }}">
                        {{ $showcaseCat->icon ?: '👗' }} {{ $showcaseCat->name }}
                    </span>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ $showcaseCat->name }}</h2>
                    @if($showcaseCat->description)
                    <p class="text-gray-500 mt-1.5 text-sm">{{ $showcaseCat->description }}</p>
                    @endif
                </div>
                <a href="{{ route('categories.show', $showcaseCat->slug) }}"
                   class="hidden md:inline-flex items-center gap-1.5 font-semibold text-sm border rounded-xl px-4 py-2 shadow-sm transition-all {{ $accent['btn'] }}">
                    View All
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
                @foreach($showcaseCat->previewDresses as $dress)
                    @include('components.dress-card', ['dress' => $dress])
                @endforeach
            </div>

            <div class="mt-6 text-center md:hidden">
                <a href="{{ route('categories.show', $showcaseCat->slug) }}"
                   class="inline-flex items-center gap-1.5 font-semibold text-sm border rounded-xl px-4 py-2 shadow-sm {{ $accent['btn'] }}">
                    View All {{ $showcaseCat->name }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>
    @endforeach
@endif

<!-- ═══════════════════ NEW ARRIVALS ═══════════════════ -->
@if($newArrivals->count())
<section class="section-amber py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="inline-block text-xs font-bold text-amber-700 uppercase tracking-widest bg-amber-200 border border-amber-300 rounded-full px-3 py-1 mb-3">✨ Just Added</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">New Arrivals</h2>
                <p class="text-gray-500 mt-1.5 text-sm">Fresh additions to our exclusive collection</p>
            </div>
            <a href="{{ route('dresses.new-arrivals') }}" class="hidden md:inline-flex items-center gap-1.5 text-amber-700 font-semibold text-sm hover:text-amber-800 border border-amber-300 bg-amber-100 rounded-xl px-4 py-2 shadow-sm hover:shadow transition-all">
                View All <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 md:gap-6">
            @foreach($newArrivals as $dress)
                @include('components.dress-card', ['dress' => $dress])
            @endforeach
        </div>
        <div class="mt-5 text-center md:hidden">
            <a href="{{ route('dresses.new-arrivals') }}" class="inline-flex items-center gap-1.5 text-amber-700 font-semibold text-sm border border-amber-300 bg-amber-100 rounded-xl px-4 py-2 shadow-sm">
                View All New Arrivals
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- ═══════════════════ CTA BANNER ═══════════════════ -->
<section class="py-8 px-4">
    <div class="max-w-5xl mx-auto rounded-3xl gradient-hero text-white overflow-hidden relative">
        <!-- Decorative circles -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-rose-500/20 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        <div class="relative px-8 py-14 text-center">
            <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 rounded-full px-4 py-1.5 text-sm font-semibold mb-6">
                🎉 Limited Time Offer
            </div>
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4 leading-tight">Ready to Look Stunning?</h2>
            <p class="text-violet-200 mb-8 max-w-lg mx-auto text-base leading-relaxed">
                Join thousands of customers renting premium dresses for weddings, parties &amp; every occasion.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dresses.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-primary-700 font-extrabold px-9 py-4 rounded-2xl hover:bg-amber-50 transition-all shadow-xl text-base">
                    Explore Dresses
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @guest
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white/15 border-2 border-white/40 text-white font-bold px-9 py-4 rounded-2xl hover:bg-white/25 transition-all text-base">
                    Create Free Account
                </a>
                @endguest
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function featuredSlider() {
    const GAP_PX = 16;             // matches gap: 1rem in .fs-track
    const DRAG_THRESHOLD = 50;     // px to register drag as a slide change
    const AUTOPLAY_INTERVAL_MS = 4000;

    return {
        current: 0,
        total: 0,
        dragging: false,
        hasDragged: false,
        startX: 0,
        trackOffset: 0,
        baseOffset: 0,
        autoTimer: null,

        init() {
            this.total = this.$refs.track.children.length;
            this.snapTo(0);
            this.startAutoplay();
            // Recalculate on resize (card width is clamp-based)
            const ro = new ResizeObserver(() => this.snapTo(this.current));
            ro.observe(this.$refs.track.parentElement);
        },

        cardWidth() {
            const card = this.$refs.track.children[0];
            return card ? card.offsetWidth : 0;
        },

        snapTo(idx) {
            this.current = Math.max(0, Math.min(idx, this.total - 1));
            this.baseOffset = -(this.current * (this.cardWidth() + GAP_PX));
            this.trackOffset = this.baseOffset;
        },

        goTo(idx) { this.snapTo(idx); },

        next() { this.snapTo(this.current < this.total - 1 ? this.current + 1 : 0); },
        prev() { this.snapTo(this.current > 0 ? this.current - 1 : this.total - 1); },

        startAutoplay() {
            this.autoTimer = setInterval(() => this.next(), AUTOPLAY_INTERVAL_MS);
        },
        pauseAutoplay() {
            clearInterval(this.autoTimer);
            this.autoTimer = null;
        },
        resumeAutoplay() {
            if (!this.autoTimer) this.startAutoplay();
        },
        resetAutoplay() {
            this.pauseAutoplay();
            this.resumeAutoplay();
        },

        startDrag(e) {
            this.pauseAutoplay();
            this.dragging = true;
            this.hasDragged = false;
            this.startX = e.clientX;
            // Attach a document-level pointerup fallback so a drag that ends
            // outside the slider viewport still resets drag state.
            // (setPointerCapture was removed because it redirects the subsequent
            // click event to the wrapper div, preventing <a> card navigation.)
            if (this._docUp) document.removeEventListener('pointerup', this._docUp);
            this._docUp = (ev) => this.endDrag(ev);
            document.addEventListener('pointerup', this._docUp);
        },

        onDrag(e) {
            if (!this.dragging) return;
            const diff = e.clientX - this.startX;
            if (Math.abs(diff) > 5) this.hasDragged = true;
            this.trackOffset = this.baseOffset + diff;
        },

        endDrag(e) {
            if (this._docUp) { document.removeEventListener('pointerup', this._docUp); this._docUp = null; }
            if (!this.dragging) return;
            this.dragging = false;
            const diff = e.clientX - this.startX;
            if (this.hasDragged && Math.abs(diff) >= DRAG_THRESHOLD) {
                if (diff < 0) this.next();
                else this.prev();
            } else {
                this.trackOffset = this.baseOffset; // snap back
                this.hasDragged = false;             // allow the click to navigate
            }
            this.resumeAutoplay();
        },

        cancelDrag() {
            if (this._docUp) { document.removeEventListener('pointerup', this._docUp); this._docUp = null; }
            if (!this.dragging) return;
            this.dragging = false;
            this.hasDragged = false;
            this.trackOffset = this.baseOffset;
            this.resumeAutoplay();
        },
    };
}

function heroSlider(total, interval, animation) {
    return {
        current: 0,
        leaving: -1,
        total: total,
        timer: null,

        init() {
            if (this.total > 1) this.startAutoplay();
        },

        startAutoplay() {
            this.timer = setInterval(() => this.next(), interval);
        },

        pause() { clearInterval(this.timer); this.timer = null; },

        resume() { if (!this.timer && this.total > 1) this.startAutoplay(); },

        goTo(idx) {
            if (idx === this.current) return;
            this.leaving = this.current;
            this.current = idx;
            setTimeout(() => { this.leaving = -1; }, 800);
            this.pause(); this.resume();
        },

        next() { this.goTo((this.current + 1) % this.total); },
        prev() { this.goTo((this.current - 1 + this.total) % this.total); },
    };
}
</script>
@endpush
