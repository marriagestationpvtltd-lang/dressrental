@extends('layouts.app')

@section('title', $dress->name)

@push('styles')
<style>
.thumb-active { border-color: #7c3aed !important; box-shadow: 0 0 0 2px #c4b5fd; }

/* Accessories horizontal slider */
.accessories-slider {
    display: flex;
    gap: 0.75rem;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE/Edge */
    scroll-behavior: smooth;
}
.accessories-slider::-webkit-scrollbar { display: none; }
.accessories-slider-item {
    flex-shrink: 0;
    width: 9rem;
    scroll-snap-align: start;
}
@media (min-width: 640px) {
    .accessories-slider-item { width: 10rem; }
}

/* Accessory card selected state */
.acc-card-selected {
    border-color: #a21caf !important;
    box-shadow: 0 0 0 2px #e879f9;
}

/* Size/Color pill buttons */
.spec-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.35rem 0.85rem;
    border-radius: 9999px;
    border: 2px solid #ddd6fe;
    font-size: 0.8125rem;
    font-weight: 700;
    color: #4c1d95;
    background: #fff;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    cursor: default;
}

/* Sticky booking bar */
#sticky-booking-bar {
    transition: transform 0.25s ease, opacity 0.25s ease;
}
#sticky-booking-bar.hidden-bar {
    transform: translateY(-100%);
    opacity: 0;
    pointer-events: none;
}

/* Zoom popup: image scale-in animation */
@keyframes zoomImgIn {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1); }
}
.zoom-img-container {
    animation: zoomImgIn 0.2s ease-out both;
}
</style>
@endpush

@section('content')

<!-- Breadcrumb bar -->
<div class="bg-white border-b border-violet-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center justify-between gap-4">
            <nav class="text-sm text-gray-400 flex items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors font-medium">Home</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('dresses.index') }}" class="hover:text-primary-600 transition-colors font-medium">Dresses</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-700 font-semibold truncate max-w-xs">{{ $dress->name }}</span>
            </nav>
            <x-share-button
                :url="route('dresses.show', $dress->slug)"
                :title="$dress->name . ' — ' . config('app.name')"
                size="sm"
            />
        </div>
    </div>
</div>

<!-- ── Sticky Booking Bar ── -->
<div id="sticky-booking-bar" class="hidden-bar fixed top-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-b border-violet-100 shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <span class="font-extrabold text-primary-600 text-lg leading-none">₨{{ number_format($dress->price_per_day) }}</span>
            <span class="text-gray-400 text-xs">/ day</span>
            <span class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold {{ $dress->status === 'available' ? 'text-emerald-600' : 'text-rose-600' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $dress->status === 'available' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                {{ ucfirst($dress->status) }}
            </span>
            <span class="hidden md:block text-sm font-bold text-gray-900 truncate">{{ $dress->name }}</span>
        </div>
        @if($dress->status === 'available')
        <a href="#booking-form"
           @click.prevent="document.getElementById('booking-form').scrollIntoView({behavior:'smooth'}); window.dispatchEvent(new CustomEvent('open-booking'))"
           class="flex-shrink-0 gradient-bg text-white font-extrabold px-5 py-2 rounded-xl text-sm hover:opacity-90 transition-opacity shadow touch-manipulation flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Book Now
        </a>
        @endif
    </div>
</div>

<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-5">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-10">

            <!-- ── Images Gallery (60%) ── -->
            <div class="flex flex-col gap-3 lg:col-span-3">
            @php
                $imageUrls    = $dress->images->pluck('url')->values()->toArray();
                $primaryImage = $dress->images->firstWhere('is_primary', true) ?? $dress->images->first();
                $primaryUrl   = $primaryImage ? $primaryImage->url : ($imageUrls[0] ?? '');
                $foundIdx     = array_search($primaryUrl, $imageUrls);
                $initialIdx   = ($foundIdx !== false) ? (int) $foundIdx : 0;
            @endphp
            <div x-data="{
                    images: @json($imageUrls),
                    activeIdx: {{ $initialIdx }},
                    get activeImg() { return this.images[this.activeIdx] || this.images[0] || ''; },
                    zoom: false,
                    openZoom() { if (this.images.length) this.zoom = true; },
                    prevImg() { this.activeIdx = (this.activeIdx - 1 + this.images.length) % this.images.length; },
                    nextImg() { this.activeIdx = (this.activeIdx + 1) % this.images.length; }
                 }">
                <!-- Main image -->
                <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-violet-50 to-pink-50 mb-3 border border-violet-100 shadow-card relative group cursor-zoom-in"
                     style="aspect-ratio:3/4; max-height:70vh;"
                     @click="openZoom()">
                    @if($primaryImage)
                        <img :src="activeImg || '{{ $primaryUrl }}'"
                             src="{{ $primaryUrl }}"
                             alt="{{ $dress->name }}"
                             class="w-full h-full object-cover object-center block"
                             id="main-img">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-100 to-pink-100">
                            <svg class="w-24 h-24 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                        </div>
                    @endif
                    @if($dress->is_featured)
                        <div class="absolute top-3 left-3 bg-amber-400 text-amber-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">⭐ Featured</div>
                    @endif
                    @if($dress->images->count() > 1)
                    <!-- Prev/Next navigation arrows on main image -->
                    <button @click.stop="prevImg()"
                            aria-label="Previous image"
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center text-white cursor-pointer transition-all shadow-md opacity-0 group-hover:opacity-100 z-10">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button @click.stop="nextImg()"
                            aria-label="Next image"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center text-white cursor-pointer transition-all shadow-md opacity-0 group-hover:opacity-100 z-10">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <!-- Image counter -->
                    <div aria-live="polite"
                         :aria-label="'Image ' + (activeIdx + 1) + ' of ' + images.length"
                         class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/50 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full select-none pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">
                        <span x-text="activeIdx + 1"></span>&thinsp;/&thinsp;<span x-text="images.length"></span>
                    </div>
                    @endif
                    <!-- Zoom hint -->
                    @if($primaryImage)
                    <div class="absolute bottom-3 right-3 bg-black/40 text-white text-[10px] font-semibold px-2 py-1 rounded-lg flex items-center gap-1 opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition-opacity pointer-events-none">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        Zoom
                    </div>
                    @endif
                </div>

                <!-- Main image zoom popup — with prev/next navigation and accessories footer -->
                <template x-teleport="body">
                    <div x-show="zoom"
                         x-cloak
                         role="dialog"
                         aria-modal="true"
                         aria-label="{{ $dress->name }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="zoom = false"
                         @keydown.escape.window="zoom = false"
                         @keydown.arrow-left.window="zoom && images.length > 1 && prevImg()"
                         @keydown.arrow-right.window="zoom && images.length > 1 && nextImg()"
                         class="fixed inset-0 z-[200] flex flex-col bg-black/80 backdrop-blur-sm"
                         style="display:none">

                        <!-- Image area — clicking the empty backdrop closes the popup -->
                        <div class="flex-1 flex items-center justify-center min-h-0 p-4 sm:p-6">
                            <div class="zoom-img-container relative" @click.stop>
                                <img :src="activeImg"
                                     alt="{{ $dress->name }}"
                                     class="max-w-[90vw] max-h-[82vh] w-auto h-auto block rounded-2xl shadow-2xl object-contain">

                                <!-- Close -->
                                <button @click.stop="zoom = false"
                                        aria-label="Close image zoom"
                                        class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-500 hover:text-gray-900 transition-colors z-10">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                @if($dress->images->count() > 1)
                                <!-- Prev -->
                                <button @click.stop="prevImg()"
                                        aria-label="Previous image"
                                        class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center text-white transition-colors shadow-md">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <!-- Next -->
                                <button @click.stop="nextImg()"
                                        aria-label="Next image"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center text-white transition-colors shadow-md">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                <!-- Image counter -->
                                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-black/60 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full select-none pointer-events-none">
                                    <span x-text="activeIdx + 1"></span>&thinsp;/&thinsp;<span x-text="images.length"></span>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </template>

                @if($dress->images->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($dress->images as $i => $img)
                        <button @click="activeIdx = {{ $i }}"
                                :class="activeIdx === {{ $i }} ? 'thumb-active' : 'border-gray-200'"
                                class="aspect-square rounded-xl overflow-hidden border-2 hover:border-primary-400 transition-all focus:outline-none">
                            <img src="{{ $img->url }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- ── Recommended Accessories (horizontal slider) ── -->
            @if($ornamentRecommendations->count())
            <div class="bg-white rounded-2xl border border-violet-100 shadow-sm p-4"
                 x-data="{
                     peek() {
                         const el = this.$refs.slider;
                         if (!el || el.scrollWidth <= el.clientWidth) return;
                         setTimeout(() => {
                             el.scrollTo({ left: 96, behavior: 'smooth' });
                             setTimeout(() => el.scrollTo({ left: 0, behavior: 'smooth' }), 700);
                         }, 600);
                     },
                     zoom: null,
                     openZoom(src, name) {
                         this.zoom = { src, name };
                     },
                     isSelected(id) {
                         return $store.accessories.selected.includes(id);
                     },
                     toggle(id) {
                         $store.accessories.toggle(id);
                     }
                 }"
                 x-init="peek()">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 gradient-bg rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">Recommended Accessories</h3>
                            <p class="text-xs text-gray-400">
                                Select to add &amp; book together
                                <span x-show="$store.accessories.selected.length > 0"
                                      class="ml-1 font-bold text-fuchsia-600"
                                      x-text="'(' + $store.accessories.selected.length + ' selected)'"></span>
                            </p>
                        </div>
                    </div>
                    @if($ornamentRecommendations->count() > 3)
                    <span class="flex items-center gap-1 text-[10px] text-fuchsia-500 font-medium select-none flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        swipe
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                    @endif
                </div>
                <div class="accessories-slider" x-ref="slider">
                    @foreach($ornamentRecommendations as $ornament)
                    <div class="accessories-slider-item group bg-gray-50 border-2 border-violet-100 rounded-xl overflow-hidden transition-all cursor-pointer"
                         :class="isSelected({{ $ornament->id }}) ? 'acc-card-selected bg-fuchsia-50' : 'hover:border-fuchsia-300 hover:shadow-sm'"
                         role="button"
                         tabindex="0"
                         :aria-pressed="isSelected({{ $ornament->id }})"
                         aria-label="{{ $ornament->name }} — ₨{{ number_format($ornament->price_per_day) }} per day"
                         @click="toggle({{ $ornament->id }})"
                         @keydown.enter.prevent="toggle({{ $ornament->id }})"
                         @keydown.space.prevent="toggle({{ $ornament->id }})">
                        <div class="relative overflow-hidden bg-gradient-to-br from-fuchsia-50 to-pink-50"
                             style="aspect-ratio:4/3;"
                             data-zoom-src="{{ $ornament->image_url }}"
                             data-zoom-name="{{ $ornament->name }}"
                             @click.stop="openZoom($el.dataset.zoomSrc, $el.dataset.zoomName)">
                            <img src="{{ $ornament->image_url }}"
                                 alt="{{ $ornament->name }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <span class="absolute top-1 left-1 bg-white/90 text-fuchsia-700 text-[10px] font-semibold px-1.5 py-0.5 rounded-full border border-fuchsia-100 leading-none">
                                {{ \App\Models\Ornament::categoryLabel($ornament->category) }}
                            </span>
                            <!-- Checkbox overlay -->
                            <div class="absolute top-1 right-1">
                                <div :class="isSelected({{ $ornament->id }})
                                         ? 'bg-fuchsia-600 border-fuchsia-600'
                                         : 'bg-white/80 border-gray-300'"
                                     class="w-5 h-5 rounded border-2 flex items-center justify-center shadow transition-colors">
                                    <svg x-show="isSelected({{ $ornament->id }})" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <p class="text-xs font-semibold text-gray-800 truncate leading-tight" title="{{ $ornament->name }}">{{ $ornament->name }}</p>
                            <p class="text-sm text-primary-600 font-extrabold mt-0.5">₨{{ number_format($ornament->price_per_day) }}<span class="text-gray-400 font-normal text-xs"> / day</span></p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Zoom popup — teleported to <body> to avoid overflow/z-index clipping -->
                <template x-teleport="body">
                    <div x-show="zoom"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="zoom = null"
                         @keydown.escape.window="zoom = null"
                         class="fixed inset-0 z-[200] flex items-center justify-center bg-black/65 backdrop-blur-sm"
                         style="display:none">
                        <div class="relative"
                             @click.stop
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90">
                            <img :src="zoom?.src"
                                 :alt="zoom?.name"
                                 class="max-w-[82vw] max-h-[72vh] w-auto h-auto rounded-2xl shadow-2xl object-contain">
                            <p class="mt-2 text-center text-white text-sm font-semibold drop-shadow" x-text="zoom?.name"></p>
                            <button @click="zoom = null"
                                    aria-label="Close"
                                    class="absolute -top-3 -right-3 w-7 h-7 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-500 hover:text-gray-900 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            @endif
            </div>{{-- end of left column wrapper --}}

        <!-- Dress Details + Booking -->
        <div class="lg:col-span-2">
                <!-- Category & Status badges -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="bg-primary-100 text-primary-700 border border-primary-200 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $dress->category->name ?? '' }}
                    </span>
                    <span class="text-xs font-bold px-3 py-1 rounded-full border
                        {{ $dress->status === 'available'
                            ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
                            : 'bg-rose-100 text-rose-700 border-rose-200' }}">
                        <span class="inline-block w-1.5 h-1.5 rounded-full mr-1
                            {{ $dress->status === 'available' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        {{ ucfirst($dress->status) }}
                    </span>
                </div>

                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 leading-tight">{{ $dress->name }}</h1>

                <!-- Price — high impact hierarchy -->
                <div class="mb-4">
                    <div class="flex items-end gap-2">
                        <span class="text-4xl md:text-5xl font-extrabold text-primary-600 leading-none">₨{{ number_format($dress->price_per_day) }}</span>
                        <span class="text-gray-400 font-medium text-sm mb-1">/ day</span>
                    </div>
                    @if($dress->deposit_amount > 0)
                        <p class="text-sm text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            Refundable Deposit: <strong class="text-gray-700 ml-0.5">₨{{ number_format($dress->deposit_amount) }}</strong>
                        </p>
                    @endif
                    @if($dress->status === 'available')
                        <p class="text-sm font-semibold text-emerald-600 mt-1.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                            Available Today
                        </p>
                    @endif
                </div>

                <!-- Specs as visual pills -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @if($dress->size)
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Size:</span>
                        <span class="spec-pill">{{ $dress->size }}</span>
                    </div>
                    @endif
                    @if($dress->color)
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Color:</span>
                        <span class="spec-pill flex items-center gap-1.5">
                            @php $safeColor = preg_replace('/[^a-zA-Z0-9#]/', '', $dress->color); @endphp
                            <span class="w-3 h-3 rounded-full border border-gray-300 inline-block"
                                  role="img"
                                  aria-label="Color: {{ $dress->color }}"
                                  style="background:{{ $safeColor }}"></span>
                            {{ $dress->color }}
                        </span>
                    </div>
                    @endif
                    @if($dress->brand)
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Brand:</span>
                        <span class="spec-pill">{{ $dress->brand }}</span>
                    </div>
                    @endif
                </div>

                @if($dress->description)
                <div class="bg-white border border-violet-100 rounded-xl px-4 py-3 mb-4 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-1.5 flex items-center gap-2 text-sm">
                        <span class="w-4 h-4 gradient-bg rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        </span>
                        Description
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-sm">{{ $dress->description }}</p>
                </div>
                @endif

                <!-- ── Booking Form ── -->
                @if($dress->status === 'available')
                {{-- overflow-visible is intentional: the Nepali calendar popup must not be clipped --}}
                <div id="booking-form" class="bg-white rounded-2xl border-2 border-violet-200 shadow-card">
                    <!-- Booking header -->
                    <div class="gradient-bg px-4 py-3 rounded-t-2xl flex items-center justify-between gap-3">
                        <h3 class="font-extrabold text-white text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Book This Dress
                        </h3>
                    </div>

                    <div class="p-4">
                    @auth
                    <form method="POST" action="{{ route('bookings.store') }}"
                          x-data="bookingForm()"
                          @submit.prevent="submitBooking($el)"
                          @dates-selected="startDate = $event.detail.startAd; endDate = $event.detail.endAd; checkAvailability(); calendarOpen = false"
                          @dates-cleared="startDate = ''; endDate = ''; available = null; amounts = null; startBsDate = ''; endBsDate = ''; calendarOpen = false"
                          @bs-start-selected="startBsDate = $event.detail.bs"
                          @bs-end-selected="endBsDate = $event.detail.bs"
                          @open-booking.window="bookingOpen = true; calendarOpen = true; $nextTick(() => $el.scrollIntoView({behavior:'smooth', block:'nearest'}))">
                        @csrf
                        <input type="hidden" name="dress_id" value="{{ $dress->id }}">
                        <input type="hidden" name="start_date" x-model="startDate">
                        <input type="hidden" name="end_date" x-model="endDate">
                        <!-- Hidden inputs for selected accessories — one per selected ornament -->
                        <template x-for="ornamentId in $store.accessories.selected" :key="ornamentId">
                            <input type="hidden" name="ornaments[]" :value="ornamentId">
                        </template>

                        <!-- Initial "Book Now" CTA — shown before booking form is opened -->
                        <div x-show="!bookingOpen" class="text-center py-2">
                            <p class="text-gray-400 text-xs mb-3">
                                <span class="font-extrabold text-primary-600 text-lg">₨{{ number_format($dress->price_per_day) }}</span>/day
                                @if($dress->deposit_amount > 0)
                                    &nbsp;+ ₨{{ number_format($dress->deposit_amount) }} deposit
                                @endif
                            </p>
                            <button type="button"
                                    @click="bookingOpen = true; $nextTick(() => calendarOpen = true)"
                                    class="w-full gradient-bg text-white font-extrabold py-4 rounded-xl text-base transition-all shadow-glow-primary hover:opacity-90 active:scale-[0.98] flex items-center justify-center gap-2 touch-manipulation">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Book Now
                            </button>
                        </div>

                        <!-- Booking details — shown after Book Now is clicked -->
                        <div x-show="bookingOpen">
                        <!-- Calendar mode toggle — compact pill -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider flex-shrink-0">Calendar:</span>
                            <div class="flex gap-1 p-0.5 bg-gray-100 rounded-lg border border-gray-200">
                                <button type="button"
                                        @click="calendarMode = 'bs'; startDate = ''; endDate = ''; available = null; amounts = null; startBsDate = ''; endBsDate = ''; calendarOpen = false"
                                        :class="calendarMode === 'bs' ? 'gradient-bg text-white shadow-sm' : 'text-gray-600 hover:bg-white'"
                                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all touch-manipulation">
                                    📅 नेपाली (BS)
                                </button>
                                <button type="button"
                                        @click="calendarMode = 'ad'; startDate = ''; endDate = ''; available = null; amounts = null; startBsDate = ''; endBsDate = ''; calendarOpen = false"
                                        :class="calendarMode === 'ad' ? 'gradient-bg text-white shadow-sm' : 'text-gray-600 hover:bg-white'"
                                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all touch-manipulation">
                                    📅 English (AD)
                                </button>
                            </div>
                        </div>

                        <!-- Date selection -->
                        <div class="mb-3">
                            <!-- BS Calendar (popup triggered by date inputs) -->
                            <div x-show="calendarMode === 'bs'" class="relative" @click.outside="calendarOpen = false">
                                <!-- Date display / trigger inputs -->
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">🟢 Start (BS)</label>
                                        <div @click="calendarOpen = !calendarOpen"
                                             :class="calendarOpen ? 'border-primary-400 ring-2 ring-primary-200 bg-primary-50' : 'border-violet-200 hover:border-primary-300 bg-violet-50/50'"
                                             class="w-full rounded-xl px-3 py-2.5 text-sm cursor-pointer flex items-center gap-2 transition-colors border touch-manipulation">
                                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span :class="startBsDate ? 'text-gray-800 font-semibold' : 'text-gray-400'"
                                                  class="text-xs"
                                                  x-text="startBsDate ? formatBsDate(startBsDate) : 'सुरु मिति'"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">🔴 End (BS)</label>
                                        <div @click="calendarOpen = !calendarOpen"
                                             :class="calendarOpen ? 'border-primary-400 ring-2 ring-primary-200 bg-primary-50' : 'border-violet-200 hover:border-primary-300 bg-violet-50/50'"
                                             class="w-full rounded-xl px-3 py-2.5 text-sm cursor-pointer flex items-center gap-2 transition-colors border touch-manipulation">
                                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span :class="endBsDate ? 'text-gray-800 font-semibold' : 'text-gray-400'"
                                                  class="text-xs"
                                                  x-text="endBsDate ? formatBsDate(endBsDate) : 'अन्त्य मिति'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Calendar popup — rendered outside overflow constraints -->
                                <div x-show="calendarOpen"
                                     role="dialog"
                                     aria-modal="true"
                                     aria-label="नेपाली मिति छान्ने क्यालेन्डर"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-1"
                                     class="absolute z-50 left-0 right-0 top-full mt-2 bg-white rounded-2xl border-2 border-violet-200 shadow-2xl max-h-[85vh] overflow-y-auto">
                                    @include('components.nepali-datepicker')
                                </div>
                            </div>

                            <!-- AD Date pickers -->
                            <div x-show="calendarMode === 'ad'" class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">🟢 Start Date</label>
                                    <input type="date" x-model="startDate" @change="checkAvailability()"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-400 outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">🔴 End Date</label>
                                    <input type="date" x-model="endDate" @change="checkAvailability()"
                                           :min="startDate || '{{ date('Y-m-d') }}'"
                                           class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-400 outline-none transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Checking state -->
                        <div x-show="checking" class="text-center py-3 text-sm text-gray-500 flex items-center justify-center gap-2 bg-gray-50 rounded-xl mb-3">
                            <svg class="w-4 h-4 animate-spin text-primary-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            उपलब्धता जाँच गर्दैछ...
                        </div>

                        <!-- Available — breakdown -->
                        <div x-show="!checking && available === true" x-cloak
                             class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-3 mb-3">
                            <div class="flex items-center gap-2 text-emerald-700 font-extrabold mb-2 text-sm">
                                <div class="w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                उपलब्ध छ! 🎉
                            </div>
                            <div class="grid grid-cols-2 gap-1.5" x-show="amounts">
                                <div class="bg-white rounded-lg p-2 border border-emerald-100 text-center">
                                    <div class="text-[10px] text-gray-400 font-semibold mb-0.5">दिनहरू</div>
                                    <div class="font-extrabold text-gray-900 text-sm" x-text="amounts?.total_days"></div>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-emerald-100 text-center">
                                    <div class="text-[10px] text-gray-400 font-semibold mb-0.5">ड्रेस भाडा</div>
                                    <div class="font-extrabold text-gray-900 text-sm">
                                        <span class="text-xs">₨</span><span x-text="formatAmount(amounts?.dress_rental)"></span>
                                    </div>
                                </div>
                                <!-- Accessories sub-total — only shown when at least one is selected -->
                                <template x-if="$store.accessories.selected.length > 0">
                                    <div class="bg-fuchsia-50 rounded-lg p-2 border border-fuchsia-200 text-center">
                                        <div class="text-[10px] text-fuchsia-600 font-semibold mb-0.5">एक्सेसरी भाडा</div>
                                        <div class="font-extrabold text-fuchsia-700 text-sm">
                                            <span class="text-xs">₨</span><span x-text="formatAmount(accessoriesRental())"></span>
                                        </div>
                                    </div>
                                </template>
                                <div class="bg-white rounded-lg p-2 border border-emerald-100 text-center">
                                    <div class="text-[10px] text-gray-400 font-semibold mb-0.5">धरौटी</div>
                                    <div class="font-extrabold text-gray-900 text-sm">
                                        <span class="text-xs">₨</span><span x-text="formatAmount(totalDeposit())"></span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-emerald-100 text-center col-span-2">
                                    <div class="text-[10px] text-gray-400 font-semibold mb-0.5">जम्मा</div>
                                    <div class="font-extrabold text-gray-900 text-base">
                                        <span class="text-xs">₨</span><span x-text="formatAmount(grandTotal())"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 gradient-bg text-white rounded-lg px-3 py-2 text-center text-xs font-extrabold">
                                अग्रिम (५०%): ₨<span x-text="formatAmount(advanceAmount())"></span>
                            </div>
                        </div>

                        <!-- Not available -->
                        <div x-show="!checking && available === false" x-cloak
                             class="bg-rose-50 border-2 border-rose-200 rounded-xl p-3 mb-3 flex items-start gap-2">
                            <div class="w-5 h-5 bg-rose-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <p class="text-rose-700 font-semibold text-sm">छानिएको मितिमा उपलब्ध छैन। कृपया अर्को मिति रोज्नुहोस्।</p>
                        </div>

                        <!-- Notes — collapsed by default -->
                        <div class="mb-3" x-data="{ open: false }">
                            <button type="button" @click="open = !open"
                                    :aria-expanded="open"
                                    class="flex items-center gap-1 text-xs font-semibold text-gray-400 hover:text-primary-600 transition-colors mb-1 touch-manipulation">
                                <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                <span x-text="open ? 'Hide note' : '+ Add special note (optional)'"></span>
                            </button>
                            <div x-show="open" x-collapse>
                                <textarea name="notes" rows="2" placeholder="कुनै विशेष अनुरोध वा टिप्पणी..."
                                          class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-400 outline-none resize-none transition-colors"></textarea>
                            </div>
                        </div>

                        <button type="submit"
                                :disabled="!available || !startDate || !endDate"
                                :class="available && startDate && endDate
                                    ? 'gradient-bg hover:opacity-90 cursor-pointer shadow-glow-primary active:scale-[0.98]'
                                    : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                class="w-full text-white font-extrabold py-4 rounded-xl text-base transition-all shadow-lg flex items-center justify-center gap-2 touch-manipulation">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Confirm &amp; Pay
                        </button>
                        </div>{{-- end x-show="bookingOpen" --}}
                    </form>
                    @else
                    <div class="text-center py-6">
                        <div class="w-14 h-14 bg-violet-100 border-2 border-violet-200 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-3">🔐</div>
                        <p class="text-gray-600 mb-4 font-medium">यो ड्रेस बुक गर्न लगइन गर्नुहोस्</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 gradient-bg text-white font-extrabold px-8 py-3.5 rounded-2xl hover:opacity-90 transition-opacity shadow-lg touch-manipulation">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            लगइन गर्नुहोस्
                        </a>
                    </div>
                    @endauth
                    </div>
                </div>
                @else
                <div class="bg-rose-50 border-2 border-rose-200 rounded-2xl p-5 text-center">
                    <div class="w-12 h-12 bg-rose-100 border border-rose-200 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3">❌</div>
                    <p class="text-rose-700 font-bold">This dress is currently unavailable</p>
                    <p class="text-rose-500 text-sm mt-1">Check back later or browse other dresses</p>
                    <a href="{{ route('dresses.index') }}" class="inline-flex items-center gap-2 mt-4 text-primary-600 font-semibold text-sm hover:underline">
                        Browse More Dresses →
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- ── Recommendations ── -->
        @if($recommendations->count())
        <section class="mt-16 pt-10 border-t-2 border-dashed border-violet-100">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="inline-block text-xs font-bold text-primary-600 uppercase tracking-widest bg-primary-50 border border-primary-200 rounded-full px-3 py-1 mb-2">You May Like</span>
                    <h2 class="text-xl md:text-2xl font-extrabold text-gray-900">Similar Dresses</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
                @foreach($recommendations as $rec)
                    @include('components.dress-card', ['dress' => $rec])
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>

@push('scripts')
<script>
const bookedRanges = @json($bookedRanges);

// Ornament prices available client-side for instant total recalculation
const ornamentPrices = @json(
    $ornamentRecommendations->mapWithKeys(fn($o) => [
        $o->id => [
            'price_per_day'  => (float) $o->price_per_day,
            'deposit_amount' => (float) $o->deposit_amount,
        ]
    ])
);

// Alpine.js global store — shared between the accessories slider and the booking form
document.addEventListener('alpine:init', () => {
    Alpine.store('accessories', {
        selected: [],
        toggle(id) {
            const idx = this.selected.indexOf(id);
            if (idx === -1) {
                this.selected.push(id);
            } else {
                this.selected.splice(idx, 1);
            }
        },
    });
});

function bookingForm() {
    return {
        startDate: '',
        endDate: '',
        startBsDate: '',
        endBsDate: '',
        calendarMode: 'bs',
        calendarOpen: false,
        bookingOpen: false,
        checking: false,
        available: null,
        // amounts holds the base dress-only breakdown returned by the server
        amounts: null,

        // Converts ASCII digits in a BS date string (e.g. "2082-01-15") to Devanagari numerals
        formatBsDate(bsStr) {
            if (!bsStr) return '';
            const digits = ['०','१','२','३','४','५','६','७','८','९'];
            return bsStr.replace(/[0-9]/g, d => digits[d]);
        },

        async checkAvailability() {
            if (!this.startDate || !this.endDate) return;
            this.checking = true;
            this.available = null;
            try {
                const resp = await fetch('{{ route('booking.check-availability', $dress) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ start_date: this.startDate, end_date: this.endDate }),
                });
                const data = await resp.json();
                this.available = data.available;
                // Store the base dress amounts; ornament costs are added client-side below
                this.amounts = data.amounts
                    ? {
                        ...data.amounts,
                        dress_rental:   parseFloat(data.amounts.rental_amount)  || 0,
                        rental_amount:  parseFloat(data.amounts.rental_amount)  || 0,
                        deposit_amount: parseFloat(data.amounts.deposit_amount) || 0,
                        total_amount:   parseFloat(data.amounts.total_amount)   || 0,
                        advance_amount: parseFloat(data.amounts.advance_amount) || 0,
                      }
                    : null;
            } catch (e) {
                this.available = null;
            }
            this.checking = false;
        },

        // Total ornament rental cost for the selected accessories × total days
        accessoriesRental() {
            if (!this.amounts) return 0;
            return this.$store.accessories.selected.reduce((sum, id) => {
                const p = ornamentPrices[id];
                return sum + (p ? p.price_per_day * this.amounts.total_days : 0);
            }, 0);
        },

        // Total deposit = dress deposit + each selected accessory's deposit
        totalDeposit() {
            if (!this.amounts) return 0;
            const accDeposit = this.$store.accessories.selected.reduce((sum, id) => {
                const p = ornamentPrices[id];
                return sum + (p ? p.deposit_amount : 0);
            }, 0);
            return (this.amounts.deposit_amount || 0) + accDeposit;
        },

        // Grand total = dress rental + accessory rental + all deposits
        grandTotal() {
            if (!this.amounts) return 0;
            return (this.amounts.dress_rental || 0) + this.accessoriesRental() + this.totalDeposit();
        },

        // Advance payment = 50 % of grand total (mirrors server-side setting)
        advanceAmount() {
            return Math.round(this.grandTotal() * 0.5 * 100) / 100;
        },

        formatAmount(val) {
            if (val == null) return '0';
            return parseFloat(val).toLocaleString('en-NP');
        },

        submitBooking(form) {
            if (this.available && this.startDate && this.endDate) {
                form.submit();
            }
        }
    }
}

// Sticky booking bar — show after user scrolls past the price section
(function() {
    const bar = document.getElementById('sticky-booking-bar');
    if (!bar) return;
    let ticking = false;
    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(() => {
                const threshold = 200;
                if (window.scrollY > threshold) {
                    bar.classList.remove('hidden-bar');
                } else {
                    bar.classList.add('hidden-bar');
                }
                ticking = false;
            });
            ticking = true;
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
})();
</script>
@endpush
@endsection
