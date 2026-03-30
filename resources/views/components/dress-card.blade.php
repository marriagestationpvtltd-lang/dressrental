<div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover group">
    <a href="{{ route('dresses.show', $dress) }}" class="block">
        <div class="relative overflow-hidden aspect-[3/4] bg-gray-100">
            @if($dress->primaryImage())
                <img src="{{ $dress->primaryImage()->url }}"
                     alt="{{ $dress->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100">
                    <svg class="w-12 h-12 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                </div>
            @endif
            @if($dress->is_featured)
                <div class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">Featured</div>
            @endif
            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-bold px-2 py-1 rounded-full">
                {{ $dress->size }}
            </div>
        </div>
        <div class="p-3 md:p-4">
            <div class="text-xs text-primary-600 font-medium mb-1">{{ $dress->category->name ?? '' }}</div>
            <h3 class="font-semibold text-gray-900 text-sm md:text-base line-clamp-2 mb-2">{{ $dress->name }}</h3>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-primary-600 font-bold text-base md:text-lg">₨{{ number_format($dress->price_per_day) }}</span>
                    <span class="text-xs text-gray-400">/day</span>
                </div>
                <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2 py-1 rounded-full">Book Now</span>
            </div>
        </div>
    </a>
</div>
