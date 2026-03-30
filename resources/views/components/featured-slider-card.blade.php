<a href="{{ $dress->category ? route('categories.show', $dress->category->slug) : route('dresses.show', $dress) }}"
   class="featured-slider-item group block bg-white rounded-2xl overflow-hidden shadow-card border border-violet-100 hover:border-primary-300 hover:shadow-card-hover transition-all"
   @if(!empty($ariaHidden)) aria-hidden="true" tabindex="-1" @endif>
    <div class="relative overflow-hidden aspect-[3/4] bg-gradient-to-br from-violet-50 to-pink-50">
        @if($dress->primaryImage())
            <img src="{{ $dress->primaryImage()->url }}"
                 alt="{{ $dress->name }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 ease-in-out"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-100 to-pink-100">
                <svg class="w-10 h-10 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
            </div>
        @endif
        @if($dress->is_featured)
            <div class="absolute top-2 left-2 bg-amber-400 text-amber-900 text-xs font-bold px-2 py-0.5 rounded-full shadow-sm flex items-center gap-1">⭐ Featured</div>
        @endif
        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-bold px-2 py-0.5 rounded-full shadow-sm border border-gray-200">{{ $dress->size }}</div>
    </div>
    <div class="p-3 border-t border-violet-50">
        <div class="text-xs text-primary-600 font-semibold mb-1 flex items-center gap-1 truncate">
            <span class="w-1.5 h-1.5 bg-primary-400 rounded-full flex-shrink-0 inline-block"></span>
            {{ $dress->category?->name ?? '' }}
        </div>
        <h3 class="font-bold text-gray-900 text-sm line-clamp-1 mb-2 leading-snug">{{ $dress->name }}</h3>
        <div class="flex items-center justify-between">
            <div>
                <span class="text-primary-600 font-extrabold text-base">₨{{ number_format($dress->price_per_day) }}</span>
                <span class="text-xs text-gray-400 font-medium">/day</span>
            </div>
            <span class="bg-gradient-to-r from-primary-600 to-rose-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow-sm">View</span>
        </div>
    </div>
</a>
