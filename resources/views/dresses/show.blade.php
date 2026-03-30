@extends('layouts.app')

@section('title', $dress->name)

@push('styles')
<style>
.thumb-active { border-color: #7c3aed !important; box-shadow: 0 0 0 2px #c4b5fd; }
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

<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- ── Images Gallery ── -->
            <div class="flex flex-col gap-4">
            <div x-data="{ activeImg: '{{ $dress->primaryImage() ? $dress->primaryImage()->url : '' }}' }">
                <div class="aspect-square rounded-3xl overflow-hidden bg-gradient-to-br from-violet-50 to-pink-50 mb-4 border border-violet-100 shadow-card relative group">
                    @if($dress->primaryImage())
                        <img :src="activeImg" alt="{{ $dress->name }}" class="w-full h-full object-cover" id="main-img">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-100 to-pink-100">
                            <svg class="w-24 h-24 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                        </div>
                    @endif
                    @if($dress->is_featured)
                        <div class="absolute top-4 left-4 bg-amber-400 text-amber-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">⭐ Featured</div>
                    @endif
                </div>
                @if($dress->images->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($dress->images as $img)
                        <button @click="activeImg = '{{ $img->url }}'"
                                :class="activeImg === '{{ $img->url }}' ? 'thumb-active' : 'border-gray-200'"
                                class="aspect-square rounded-xl overflow-hidden border-2 hover:border-primary-400 transition-all focus:outline-none">
                            <img src="{{ $img->url }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- ── Recommended Accessories (compact) ── -->
            @if($ornamentRecommendations->count())
            <div class="bg-white rounded-2xl border border-violet-100 shadow-sm p-4">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-5 h-5 gradient-bg rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Recommended Accessories</h3>
                        <p class="text-xs text-gray-400">Complete the look</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($ornamentRecommendations->take(6) as $ornament)
                    <div class="group bg-gray-50 border border-violet-100 rounded-xl overflow-hidden hover:border-fuchsia-300 hover:shadow-sm transition-all"
                         role="group"
                         aria-label="{{ $ornament->name }} — ₨{{ number_format($ornament->price_per_day) }} per day">
                        <div class="aspect-square bg-gradient-to-br from-fuchsia-50 to-pink-50 overflow-hidden relative">
                            <img src="{{ $ornament->image_url }}"
                                 alt="{{ $ornament->name }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <span class="absolute top-1 left-1 bg-white/90 text-fuchsia-700 text-[10px] font-semibold px-1.5 py-0.5 rounded-full border border-fuchsia-100 leading-none"
                                  aria-label="{{ \App\Models\Ornament::categoryLabel($ornament->category) }}">
                                {{ \App\Models\Ornament::categoryLabel($ornament->category) }}
                            </span>
                        </div>
                        <div class="p-1.5">
                            <p class="text-xs font-semibold text-gray-800 truncate leading-tight" title="{{ $ornament->name }}">{{ $ornament->name }}</p>
                            <p class="text-xs text-primary-600 font-bold mt-0.5">₨{{ number_format($ornament->price_per_day) }}<span class="text-gray-400 font-normal">/d</span></p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($ornamentRecommendations->count() > 6)
                <p class="text-xs text-gray-400 text-center mt-2">+{{ $ornamentRecommendations->count() - 6 }} more accessories available</p>
                @endif
            </div>
            @endif
            </div>{{-- end of left column wrapper --}}

        <!-- Dress Details + Booking -->
        <div>
                <!-- Category & Status badges -->
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-primary-100 text-primary-700 border border-primary-200 text-xs font-bold px-3 py-1.5 rounded-full">
                        {{ $dress->category->name ?? '' }}
                    </span>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full border
                        {{ $dress->status === 'available'
                            ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
                            : 'bg-rose-100 text-rose-700 border-rose-200' }}">
                        <span class="inline-block w-1.5 h-1.5 rounded-full mr-1
                            {{ $dress->status === 'available' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        {{ ucfirst($dress->status) }}
                    </span>
                </div>

                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $dress->name }}</h1>

                <!-- Price -->
                <div class="bg-gradient-to-r from-violet-50 to-pink-50 border border-violet-200 rounded-2xl px-5 py-4 mb-6">
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl md:text-4xl font-extrabold text-primary-600">₨{{ number_format($dress->price_per_day) }}</span>
                        <span class="text-gray-500 font-medium">per day</span>
                    </div>
                    @if($dress->deposit_amount > 0)
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            Refundable deposit: <strong class="text-gray-700">₨{{ number_format($dress->deposit_amount) }}</strong>
                        </p>
                    @endif
                </div>

                <!-- Specs grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                    <div class="bg-white border border-violet-100 rounded-2xl p-3 text-center shadow-sm">
                        <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Size</div>
                        <div class="font-extrabold text-gray-900 text-sm">{{ $dress->size }}</div>
                    </div>
                    @if($dress->color)
                    <div class="bg-white border border-violet-100 rounded-2xl p-3 text-center shadow-sm">
                        <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Color</div>
                        <div class="font-extrabold text-gray-900 text-sm">{{ $dress->color }}</div>
                    </div>
                    @endif
                    @if($dress->brand)
                    <div class="bg-white border border-violet-100 rounded-2xl p-3 text-center shadow-sm">
                        <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Brand</div>
                        <div class="font-extrabold text-gray-900 text-sm">{{ $dress->brand }}</div>
                    </div>
                    @endif
                </div>

                @if($dress->description)
                <div class="bg-white border border-violet-100 rounded-2xl p-5 mb-6 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <span class="w-4 h-4 gradient-bg rounded flex items-center justify-center">
                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        </span>
                        Description
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-sm">{{ $dress->description }}</p>
                </div>
                @endif

                <!-- ── Booking Form ── -->
                @if($dress->status === 'available')
                <div class="bg-white rounded-3xl border-2 border-violet-200 shadow-card overflow-hidden">
                    <!-- Booking header -->
                    <div class="gradient-bg px-6 py-4">
                        <h3 class="font-extrabold text-white text-base flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Book This Dress
                        </h3>
                        <p class="text-violet-200 text-xs mt-0.5">Select dates to check availability & pricing</p>
                    </div>

                    <div class="p-6">
                    @auth
                    <form method="POST" action="{{ route('bookings.store') }}"
                          x-data="bookingForm()"
                          @submit.prevent="submitBooking($el)"
                          @dates-selected="startDate = $event.detail.startAd; endDate = $event.detail.endAd; checkAvailability()"
                          @dates-cleared="startDate = ''; endDate = ''; available = null; amounts = null;">
                        @csrf
                        <input type="hidden" name="dress_id" value="{{ $dress->id }}">
                        <input type="hidden" name="start_date" x-model="startDate">
                        <input type="hidden" name="end_date" x-model="endDate">

                        <!-- Calendar mode toggle -->
                        <div class="mb-5">
                            <div class="flex gap-2 mb-4 p-1 bg-gray-100 rounded-xl border border-gray-200">
                                <button type="button"
                                        @click="calendarMode = 'bs'; startDate = ''; endDate = ''; available = null; amounts = null;"
                                        :class="calendarMode === 'bs' ? 'gradient-bg text-white shadow-sm' : 'text-gray-600 hover:text-gray-800'"
                                        class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-1.5">
                                    📅 नेपाली (BS)
                                </button>
                                <button type="button"
                                        @click="calendarMode = 'ad'; startDate = ''; endDate = ''; available = null; amounts = null;"
                                        :class="calendarMode === 'ad' ? 'gradient-bg text-white shadow-sm' : 'text-gray-600 hover:text-gray-800'"
                                        class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-1.5">
                                    📅 English (AD)
                                </button>
                            </div>

                            <!-- BS Calendar -->
                            <div x-show="calendarMode === 'bs'" class="bg-gray-50 rounded-2xl border border-violet-100 overflow-hidden">
                                @include('components.nepali-datepicker')
                            </div>

                            <!-- AD Date pickers -->
                            <div x-show="calendarMode === 'ad'" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Start Date</label>
                                    <input type="date" x-model="startDate" @change="checkAvailability()"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-400 outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">End Date</label>
                                    <input type="date" x-model="endDate" @change="checkAvailability()"
                                           :min="startDate || '{{ date('Y-m-d') }}'"
                                           class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-400 outline-none transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Checking state -->
                        <div x-show="checking" class="text-center py-3 text-sm text-gray-500 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin text-primary-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Checking availability...
                        </div>

                        <!-- Available -->
                        <div x-show="!checking && available === true" x-cloak
                             class="bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-5 mb-4">
                            <div class="flex items-center gap-2 text-emerald-700 font-bold mb-3">
                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                Available! 🎉
                            </div>
                            <div class="grid grid-cols-2 gap-2.5" x-show="amounts">
                                @foreach([
                                    ['label' => 'Days', 'key' => 'total_days', 'type' => 'num'],
                                    ['label' => 'Rental', 'key' => 'rental_amount', 'type' => 'price'],
                                    ['label' => 'Deposit', 'key' => 'deposit_amount', 'type' => 'price'],
                                    ['label' => 'Total', 'key' => 'total_amount', 'type' => 'price'],
                                ] as $row)
                                <div class="bg-white rounded-xl p-2.5 border border-emerald-100 text-center">
                                    <div class="text-xs text-gray-400 font-semibold">{{ $row['label'] }}</div>
                                    <div class="font-extrabold text-gray-900 text-sm mt-0.5">
                                        @if($row['type'] === 'price')₨<span x-text="formatAmount(amounts?.{{ $row['key'] }})"></span>@else<span x-text="amounts?.{{ $row['key'] }}"></span>@endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-3 bg-primary-600 text-white rounded-xl px-4 py-2.5 text-center text-sm font-bold">
                                Advance (50%): ₨<span x-text="formatAmount(amounts?.advance_amount)"></span>
                            </div>
                        </div>

                        <!-- Not available -->
                        <div x-show="!checking && available === false" x-cloak
                             class="bg-rose-50 border-2 border-rose-200 rounded-2xl p-4 mb-4 flex items-start gap-3">
                            <div class="w-6 h-6 bg-rose-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <p class="text-rose-700 font-semibold text-sm">Not available for selected dates. Please choose different dates.</p>
                        </div>

                        <!-- Notes -->
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Special Notes (optional)</label>
                            <textarea name="notes" rows="2" placeholder="Any special requests or notes..."
                                      class="w-full border border-violet-200 bg-violet-50/50 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-400 outline-none resize-none transition-colors"></textarea>
                        </div>

                        <button type="submit"
                                :disabled="!available || !startDate || !endDate"
                                :class="available && startDate && endDate
                                    ? 'gradient-bg hover:opacity-90 cursor-pointer shadow-glow-primary'
                                    : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                class="w-full text-white font-extrabold py-4 rounded-2xl text-lg transition-all shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Book Now &amp; Pay
                        </button>
                    </form>
                    @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-violet-100 border-2 border-violet-200 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4">🔐</div>
                        <p class="text-gray-600 mb-5 font-medium">Please login to book this dress</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 gradient-bg text-white font-extrabold px-8 py-3.5 rounded-2xl hover:opacity-90 transition-opacity shadow-lg">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Login to Book
                        </a>
                    </div>
                    @endauth
                    </div>
                </div>
                @else
                <div class="bg-rose-50 border-2 border-rose-200 rounded-2xl p-6 text-center">
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

function bookingForm() {
    return {
        startDate: '',
        endDate: '',
        calendarMode: 'ad',
        checking: false,
        available: null,
        amounts: null,
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
                this.amounts = data.amounts;
            } catch (e) {
                this.available = null;
            }
            this.checking = false;
        },
        formatAmount(val) {
            if (!val) return '0';
            return parseFloat(val).toLocaleString('en-NP');
        },
        submitBooking(form) {
            if (this.available && this.startDate && this.endDate) {
                form.submit();
            }
        }
    }
}
</script>
@endpush
@endsection
