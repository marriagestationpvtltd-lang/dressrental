<a href="{{ route('dresses.show', $dress) }}"
   class="fs-card group block bg-white rounded-2xl overflow-hidden shadow-card border border-gray-100 hover:shadow-card-hover transition-shadow duration-300 select-none"
   @if(!empty($ariaHidden)) aria-hidden="true" tabindex="-1" @endif>
    {{-- Image: 4:5 aspect ratio, ~67% of card height --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-violet-50 to-pink-50" style="aspect-ratio:4/5">
        @if($dress->primaryImage())
            <img src="{{ $dress->primaryImage()->url }}"
                 alt="{{ $dress->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                 loading="lazy"
                 draggable="false">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-12 h-12 text-violet-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
        @if($dress->is_featured)
            <div class="absolute top-3 left-3 bg-amber-400 text-amber-900 text-xs font-bold px-2.5 py-1 rounded-full shadow flex items-center gap-1">⭐ Featured</div>
        @endif
        @if($dress->size)
            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-full shadow border border-gray-100">{{ $dress->size }}</div>
        @endif
    </div>
    {{-- Card info: name, price, CTA --}}
    <div class="p-4">
        <h3 class="font-semibold text-gray-900 text-sm truncate leading-snug mb-1">{{ $dress->name }}</h3>
        <p class="text-xs text-gray-400 mb-3">
            <span class="font-semibold text-primary-600 text-sm">₨{{ number_format($dress->price_per_day) }}</span>/day
        </p>
        <span class="block text-center bg-gradient-to-r from-primary-600 to-rose-500 text-white text-xs font-semibold py-2 rounded-xl shadow-sm group-hover:shadow transition-shadow">View Details</span>
    </div>
</a>
