@extends('layouts.app')

@section('title', $dress->name)

@push('styles')
<style>
.thumb-active { border-color: #6d28d9 !important; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('dresses.index') }}" class="hover:text-primary-600">Dresses</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">{{ $dress->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        <!-- Images Gallery -->
        <div x-data="{ activeImg: '{{ $dress->primaryImage() ? asset('storage/' . $dress->primaryImage()->image_path) : '' }}' }">
            <div class="aspect-square rounded-3xl overflow-hidden bg-gray-100 mb-4">
                @if($dress->primaryImage())
                    <img :src="activeImg" alt="{{ $dress->name }}" class="w-full h-full object-cover" id="main-img">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100">
                        <svg class="w-24 h-24 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                    </div>
                @endif
            </div>
            @if($dress->images->count() > 1)
            <div class="grid grid-cols-5 gap-2">
                @foreach($dress->images as $img)
                    <button @click="activeImg = '{{ asset('storage/' . $img->image_path) }}'"
                            :class="activeImg === '{{ asset('storage/' . $img->image_path) }}' ? 'thumb-active' : ''"
                            class="aspect-square rounded-xl overflow-hidden border-2 border-transparent hover:border-primary-400 transition-colors">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Dress Details + Booking -->
        <div>
            <div class="flex items-start justify-between mb-2">
                <span class="bg-primary-50 text-primary-600 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $dress->category->name ?? '' }}
                </span>
                <span class="bg-{{ $dress->status === 'available' ? 'green' : 'red' }}-100 text-{{ $dress->status === 'available' ? 'green' : 'red' }}-700 text-sm font-medium px-3 py-1 rounded-full">
                    {{ ucfirst($dress->status) }}
                </span>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $dress->name }}</h1>

            <div class="flex items-baseline gap-3 mb-4">
                <span class="text-3xl font-bold text-primary-600">₨{{ number_format($dress->price_per_day) }}</span>
                <span class="text-gray-500">per day</span>
                @if($dress->deposit_amount > 0)
                    <span class="text-sm text-gray-500">+ ₨{{ number_format($dress->deposit_amount) }} deposit</span>
                @endif
            </div>

            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Size</div>
                    <div class="font-bold text-gray-900">{{ $dress->size }}</div>
                </div>
                @if($dress->color)
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Color</div>
                    <div class="font-bold text-gray-900">{{ $dress->color }}</div>
                </div>
                @endif
                @if($dress->brand)
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Brand</div>
                    <div class="font-bold text-gray-900">{{ $dress->brand }}</div>
                </div>
                @endif
            </div>

            @if($dress->description)
            <div class="mb-6">
                <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
                <p class="text-gray-600 leading-relaxed">{{ $dress->description }}</p>
            </div>
            @endif

            <!-- Booking Form -->
            @if($dress->status === 'available')
            <div class="bg-gradient-to-br from-primary-50 to-pink-50 rounded-2xl p-6 border border-primary-100">
                <h3 class="font-bold text-gray-900 mb-4 text-lg">Book This Dress</h3>

                @auth
                <form method="POST" action="{{ route('bookings.store') }}" x-data="bookingForm()" @submit.prevent="submitBooking($el)">
                    @csrf
                    <input type="hidden" name="dress_id" value="{{ $dress->id }}">
                    <input type="hidden" name="start_date" x-model="startDate">
                    <input type="hidden" name="end_date" x-model="endDate">

                    <!-- Nepali Date Picker -->
                    <div class="mb-4">
                        <div class="flex gap-2 mb-3">
                            <button type="button" @click="calendarMode = 'bs'"
                                    :class="calendarMode === 'bs' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700'"
                                    class="flex-1 py-2 rounded-xl text-sm font-medium border border-primary-200 transition-colors">
                                📅 Nepali (BS)
                            </button>
                            <button type="button" @click="calendarMode = 'ad'"
                                    :class="calendarMode === 'ad' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700'"
                                    class="flex-1 py-2 rounded-xl text-sm font-medium border border-primary-200 transition-colors">
                                📅 English (AD)
                            </button>
                        </div>

                        <!-- BS Calendar -->
                        <div x-show="calendarMode === 'bs'" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            @include('components.nepali-datepicker')
                        </div>

                        <!-- AD Date pickers -->
                        <div x-show="calendarMode === 'ad'" class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Start Date</label>
                                <input type="date" x-model="startDate" @change="checkAvailability()"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">End Date</label>
                                <input type="date" x-model="endDate" @change="checkAvailability()"
                                       :min="startDate || '{{ date('Y-m-d') }}'"
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Availability Result -->
                    <div x-show="checking" class="text-center py-3 text-sm text-gray-500">Checking availability...</div>

                    <div x-show="!checking && available === true" x-cloak class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-2 text-green-700 font-semibold mb-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Available! 🎉
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-700" x-show="amounts">
                            <div>Days: <strong x-text="amounts?.total_days"></strong></div>
                            <div>Rental: <strong>₨<span x-text="formatAmount(amounts?.rental_amount)"></span></strong></div>
                            <div>Deposit: <strong>₨<span x-text="formatAmount(amounts?.deposit_amount)"></span></strong></div>
                            <div>Total: <strong>₨<span x-text="formatAmount(amounts?.total_amount)"></span></strong></div>
                        </div>
                        <div class="mt-2 text-sm font-semibold text-primary-700">
                            Advance (50%): ₨<span x-text="formatAmount(amounts?.advance_amount)"></span>
                        </div>
                    </div>

                    <div x-show="!checking && available === false" x-cloak class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4 text-red-700 text-sm font-medium">
                        ❌ Not available for selected dates. Please choose different dates.
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Special Notes (optional)</label>
                        <textarea name="notes" rows="2" placeholder="Any special requests..."
                                  class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none resize-none"></textarea>
                    </div>

                    <button type="submit"
                            :disabled="!available || !startDate || !endDate"
                            :class="available && startDate && endDate ? 'bg-primary-600 hover:bg-primary-700 cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                            class="w-full text-white font-bold py-4 rounded-2xl text-lg transition-colors shadow-lg">
                        Book Now & Pay
                    </button>
                </form>
                @else
                <div class="text-center py-6">
                    <p class="text-gray-600 mb-4">Please login to book this dress</p>
                    <a href="{{ route('login') }}" class="bg-primary-600 text-white font-bold px-8 py-3 rounded-2xl hover:bg-primary-700 transition-colors">
                        Login to Book
                    </a>
                </div>
                @endauth
            </div>
            @else
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                <p class="text-red-700 font-semibold">This dress is currently unavailable</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recommendations -->
    @if($recommendations->count())
    <section class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($recommendations as $rec)
                @include('components.dress-card', ['dress' => $rec])
            @endforeach
        </div>
    </section>
    @endif
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
