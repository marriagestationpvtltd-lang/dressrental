@props([
    'url'   => request()->url(),
    'title' => config('app.name'),
    'size'  => 'md',
])

@php
    $encodedUrl   = urlencode($url);
    $encodedTitle = urlencode($title);
    $whatsappUrl  = "https://wa.me/?text={$encodedTitle}%20{$encodedUrl}";
    $facebookUrl  = "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}";
    $twitterUrl   = "https://twitter.com/intent/tweet?text={$encodedTitle}&url={$encodedUrl}";
    $btnClass     = $size === 'sm'
        ? 'inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg'
        : 'inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl';
@endphp

<div x-data="{ open: false, copied: false }" class="relative inline-block">
    {{-- Share trigger button --}}
    <button
        @click="
            if (typeof navigator !== 'undefined' && navigator.share) {
                navigator.share({ title: @js($title), url: @js($url) }).catch(() => {});
            } else {
                open = !open;
            }
        "
        class="{{ $btnClass }} bg-white border border-violet-200 text-primary-600 hover:bg-violet-50 hover:border-primary-400 shadow-sm transition-all"
        title="Share this page"
        type="button"
    >
        <svg class="{{ $size === 'sm' ? 'w-3.5 h-3.5' : 'w-4 h-4' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
        </svg>
        <span x-text="copied ? 'Copied!' : 'Share'"></span>
    </button>

    {{-- Dropdown (fallback for non-native share) --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="open = false"
        class="absolute right-0 mt-2 w-48 bg-white border border-violet-100 rounded-2xl shadow-lg z-50 overflow-hidden"
        style="display: none;"
    >
        {{-- Copy link --}}
        <button
            @click="
                navigator.clipboard.writeText(@js($url)).then(() => {
                    copied = true;
                    open = false;
                    setTimeout(() => copied = false, 2000);
                });
            "
            class="flex w-full items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-violet-50 transition-colors"
            type="button"
        >
            <span class="w-8 h-8 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </span>
            Copy Link
        </button>

        {{-- WhatsApp --}}
        <a
            href="{{ $whatsappUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors"
        >
            <span class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </span>
            WhatsApp
        </a>

        {{-- Facebook --}}
        <a
            href="{{ $facebookUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors"
        >
            <span class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </span>
            Facebook
        </a>

        {{-- Twitter/X --}}
        <a
            href="{{ $twitterUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
        >
            <span class="w-8 h-8 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </span>
            Twitter / X
        </a>
    </div>
</div>
