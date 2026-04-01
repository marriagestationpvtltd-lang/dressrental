<div class="bg-white rounded-2xl overflow-hidden shadow-card border border-violet-100 hover:border-primary-300 card-hover group transition-all">
    <a href="{{ route('dresses.show', $dress) }}" class="block">
        <!-- Image -->
        <div class="relative overflow-hidden aspect-[3/4] bg-gradient-to-br from-violet-50 to-pink-50">
            @php $primaryImg = $dress->primaryImage(); @endphp
            @if($primaryImg)
                <img src="{{ $primaryImg->url }}"
                     alt="{{ $dress->name }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 ease-in-out"
                     loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-100 to-pink-100">
                    <svg class="w-14 h-14 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                </div>
            @endif

            <!-- Gradient overlay on hover -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

            <!-- Badges -->
            @if($dress->is_featured)
                <div class="absolute top-2 left-2 bg-amber-400 text-amber-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                    ⭐ Featured
                </div>
            @endif
            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm border border-gray-200">
                {{ $dress->size }}
            </div>

            <!-- Quick view button -->
            <div class="absolute bottom-3 left-0 right-0 flex justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <span class="bg-white text-primary-700 text-xs font-bold px-4 py-1.5 rounded-full shadow-lg border border-primary-100">
                    Quick View →
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-3 md:p-4 border-t border-violet-50">
            <div class="text-xs text-primary-600 font-semibold mb-1 flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-primary-400 rounded-full inline-block"></span>
                {{ $dress->category->name ?? '' }}
            </div>
            <h3 class="font-bold text-gray-900 text-sm md:text-base line-clamp-2 mb-2.5 leading-snug">{{ $dress->name }}</h3>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-primary-600 font-extrabold text-base md:text-lg">₨{{ number_format($dress->price_per_day) }}</span>
                    <span class="text-xs text-gray-400 font-medium">/day</span>
                </div>
                <span class="bg-gradient-to-r from-primary-600 to-rose-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow-sm">
                    Book Now
                </span>
            </div>
        </div>
    </a>
</div>
