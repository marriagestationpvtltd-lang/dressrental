@extends('layouts.admin')
@section('title', 'Ornament Recommendations – ' . $category->name)

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-0.5">
            @if($category->parent)
                <span>{{ $category->parent->icon }} {{ $category->parent->name }}</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            @endif
            <span class="font-medium text-gray-700">{{ $category->icon }} {{ $category->name }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Ornament Recommendations</h1>
    </div>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
    ✓ {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- ── LEFT: Current recommendations ── --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-900">Current Recommendations</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Ornaments shown to customers browsing dresses in this category</p>
                </div>
                <span class="bg-primary-100 text-primary-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $recommended->count() }}</span>
            </div>

            @if($recommended->isEmpty())
            <div class="text-center py-14">
                <div class="text-4xl mb-3">💎</div>
                <p class="text-gray-400 text-sm">No recommendations yet.</p>
                <p class="text-gray-300 text-xs mt-1">Use the panel on the right to add ornaments.</p>
            </div>
            @else
            <ul id="recommendation-list" class="divide-y divide-gray-50">
                @foreach($recommended as $ornament)
                <li class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 group" data-id="{{ $ornament->id }}">
                    <div class="cursor-grab text-gray-300 group-hover:text-gray-400 shrink-0" title="Drag to reorder">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </div>
                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-violet-50 border border-violet-100 shrink-0">
                        <img src="{{ $ornament->image_url }}" alt="{{ $ornament->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-900 text-sm truncate">{{ $ornament->name }}</div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="bg-violet-100 text-violet-700 text-xs font-medium px-1.5 py-0.5 rounded-full">
                                {{ \App\Models\Ornament::categoryLabel($ornament->category) }}
                            </span>
                            <span class="text-xs text-gray-400">₨{{ number_format($ornament->price_per_day) }}/day</span>
                            @if($ornament->status === 'unavailable')
                            <span class="text-xs text-rose-500 font-medium">Unavailable</span>
                            @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.categories.ornaments.destroy', [$category, $ornament]) }}" onsubmit="return confirm('Remove from recommendations?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity" title="Remove">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        @if($category->isTopLevel() && $category->subcategories->isNotEmpty())
        <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-700">
            <strong>Tip:</strong> Recommendations set here apply to all dresses in this top-level category.
            You can set more specific recommendations per subcategory below:
            <ul class="mt-2 space-y-1">
                @foreach($category->subcategories as $sub)
                <li>
                    <a href="{{ route('admin.categories.ornaments.manage', $sub) }}" class="font-medium underline hover:text-amber-900">
                        {{ $sub->icon }} {{ $sub->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($category->parent)
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-blue-700">
            <strong>Inherited:</strong> Dresses in this subcategory also inherit recommendations from the parent category
            <a href="{{ route('admin.categories.ornaments.manage', $category->parent) }}" class="font-medium underline hover:text-blue-900">
                {{ $category->parent->name }}
            </a>.
        </div>
        @endif
    </div>

    {{-- ── RIGHT: Add ornaments ── --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-4">
            <h2 class="font-semibold text-gray-900 mb-1">Add Ornaments</h2>
            <p class="text-xs text-gray-400 mb-4">Select ornaments to add to this category's recommendations</p>

            {{-- Search filter --}}
            <form method="GET" action="{{ route('admin.categories.ornaments.manage', $category) }}" class="mb-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search available ornaments..."
                           class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
            </form>

            <form method="POST" action="{{ route('admin.categories.ornaments.store', $category) }}" id="add-form">
                @csrf
                @error('ornament_ids')
                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                @enderror

                @if($available->isEmpty())
                <p class="text-center text-gray-400 text-sm py-8">
                    {{ $search ? 'No ornaments found for "' . $search . '"' : 'All ornaments are already recommended.' }}
                </p>
                @else
                <div class="space-y-1 max-h-80 overflow-y-auto pr-1 mb-4" id="available-list">
                    @foreach($available as $ornament)
                    <label class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 cursor-pointer group">
                        <input type="checkbox" name="ornament_ids[]" value="{{ $ornament->id }}"
                               class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 shrink-0">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-violet-50 border border-violet-100 shrink-0">
                            <img src="{{ $ornament->image_url }}" alt="{{ $ornament->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-800 truncate">{{ $ornament->name }}</div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs text-gray-400">{{ \App\Models\Ornament::categoryLabel($ornament->category) }}</span>
                                <span class="text-gray-300">·</span>
                                <span class="text-xs text-gray-400">₨{{ number_format($ornament->price_per_day) }}/day</span>
                                @if($ornament->status === 'unavailable')
                                <span class="text-xs text-rose-400">(unavailable)</span>
                                @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <button type="button" onclick="toggleAll(true)" class="text-xs text-primary-600 hover:underline">Select all</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="toggleAll(false)" class="text-xs text-gray-500 hover:underline">Deselect all</button>
                </div>

                <button type="submit"
                        class="w-full bg-primary-600 text-white font-bold py-2.5 rounded-xl hover:bg-primary-700 transition-colors text-sm">
                    Add Selected Ornaments
                </button>
                @endif
            </form>
        </div>
    </div>

</div>

<script>
function toggleAll(checked) {
    document.querySelectorAll('#available-list input[type="checkbox"]').forEach(cb => {
        cb.checked = checked;
        cb.dispatchEvent(new Event('change'));
    });
}
</script>
@endsection
