@extends('layouts.admin')
@section('title', 'Edit Dress')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dresses.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit: {{ $dress->name }}</h1>
    </div>

    <!-- Existing Images -->
    @if($dress->images->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h3 class="font-semibold text-gray-900 mb-3">Current Images</h3>
        <div class="grid grid-cols-4 gap-3">
            @foreach($dress->images as $img)
            <div class="relative group">
                <img src="{{ $img->url }}" alt="" class="aspect-square rounded-xl object-cover w-full {{ $img->is_primary ? 'ring-2 ring-primary-500' : '' }}">
                @if($img->is_primary)<span class="absolute top-1 left-1 bg-primary-500 text-white text-xs px-1.5 py-0.5 rounded-full">Primary</span>@endif
                <form method="POST" action="{{ route('admin.dresses.images.destroy', $img) }}" class="absolute top-1 right-1" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.dresses.update', $dress) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dress Name *</label>
                    <input type="text" name="name" value="{{ old('name', $dress->name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>

                @include('admin.dresses._category_select', [
                    'currentCategoryId' => old('category_id', $dress->category_id),
                ])

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Sizes * <span class="text-xs text-gray-400">(select all that apply)</span></label>
                    @php $currentSizes = old('sizes', $dress->availableSizes->pluck('size')->toArray() ?: ($dress->size ? [$dress->size] : [])); @endphp
                    <div class="flex flex-wrap gap-3">
                        @foreach($sizes as $sz)
                        <label class="flex items-center gap-2 cursor-pointer select-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 hover:bg-primary-50 hover:border-primary-300 transition-colors has-[:checked]:bg-primary-50 has-[:checked]:border-primary-500">
                            <input type="checkbox" name="sizes[]" value="{{ $sz }}"
                                   class="w-4 h-4 rounded border-gray-300 text-primary-600"
                                   {{ in_array($sz, $currentSizes) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">{{ $sz }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('sizes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price per Day (₨) *</label>
                    <input type="number" name="price_per_day" value="{{ old('price_per_day', $dress->price_per_day) }}" required min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deposit Amount (₨)</label>
                    <input type="number" name="deposit_amount" value="{{ old('deposit_amount', $dress->deposit_amount) }}" min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" value="{{ old('color', $dress->color) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $dress->brand) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                        <option value="available" {{ old('status', $dress->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('status', $dress->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 rounded border-gray-300 text-primary-600" {{ old('is_featured', $dress->is_featured) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Featured Dress ⭐</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none resize-none">{{ old('description', $dress->description) }}</textarea>
                </div>

                @if($ornaments->count())
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recommended Ornaments & Accessories</label>
                    <p class="text-xs text-gray-400 mb-3">Select the ornaments/accessories that go well with this dress. These will be shown to customers on the dress detail page.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-64 overflow-y-auto border border-gray-200 rounded-xl p-3">
                        @foreach($ornaments as $ornament)
                        <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-violet-50 cursor-pointer border border-transparent has-[:checked]:border-violet-300 has-[:checked]:bg-violet-50 transition-colors">
                            <input type="checkbox" name="ornament_ids[]" value="{{ $ornament->id }}"
                                   class="w-4 h-4 rounded border-gray-300 text-primary-600"
                                   {{ in_array($ornament->id, old('ornament_ids', $dress->ornaments->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <div class="min-w-0">
                                <div class="text-sm font-medium text-gray-800 truncate">{{ $ornament->name }}</div>
                                <div class="text-xs text-gray-400">{{ \App\Models\Ornament::categoryLabel($ornament->category) }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                @php
                    $existingPricings = old('pricings', $dress->pricings->map(fn($p) => ['days' => $p->days, 'price' => $p->price])->toArray());
                @endphp
                <div class="md:col-span-2" x-data="pricingTiers({{ json_encode($existingPricings) }})">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Rental Duration Pricing <span class="text-xs text-gray-400">(optional — overrides price per day for exact day counts)</span></label>
                        <button type="button" @click="addRow()"
                                class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 border border-primary-300 rounded-lg px-3 py-1 hover:bg-primary-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Tier
                        </button>
                    </div>
                    <div x-show="rows.length > 0" class="border border-gray-200 rounded-xl overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-2 text-left">Days</th>
                                    <th class="px-4 py-2 text-left">Price (₨)</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, idx) in rows" :key="idx">
                                    <tr class="border-t border-gray-100">
                                        <td class="px-4 py-2">
                                            <input type="number" :name="`pricings[${idx}][days]`" x-model.number="row.days"
                                                   min="1" placeholder="e.g. 1"
                                                   class="w-24 border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary-500 outline-none">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="number" :name="`pricings[${idx}][price]`" x-model.number="row.price"
                                                   min="0" step="0.01" placeholder="0.00"
                                                   class="w-32 border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary-500 outline-none">
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <button type="button" @click="removeRow(idx)"
                                                    class="text-red-400 hover:text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <p x-show="rows.length === 0" class="text-xs text-gray-400 mt-1">No tiers set — pricing will use <em>Price per Day × days</em>.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Add More Images
                        @if(\App\Models\Setting::get('gemini_api_key'))
                            <span class="ml-2 inline-flex items-center gap-1 text-xs font-normal text-violet-600 bg-violet-50 border border-violet-200 rounded-full px-2 py-0.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                AI analysis available
                            </span>
                        @endif
                    </label>
                    <input type="file" id="dress-images" name="images[]" multiple accept="image/*"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none file:mr-3 file:border-0 file:bg-primary-50 file:text-primary-700 file:px-3 file:py-1 file:rounded-lg">
                    <p class="text-xs text-gray-400 mt-1">Max 3MB each. JPG, PNG, WebP.</p>
                    @include('admin.dresses._ai_generate')
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-primary-600 text-white font-bold py-3 rounded-2xl hover:bg-primary-700 transition-colors">
                    Update Dress
                </button>
                <a href="{{ route('admin.dresses.index') }}" class="flex-1 border border-gray-200 text-gray-700 font-medium py-3 rounded-2xl text-center hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function pricingTiers(initial) {
    return {
        rows: initial.length ? initial : [],
        addRow() { this.rows.push({ days: '', price: '' }); },
        removeRow(idx) { this.rows.splice(idx, 1); },
    };
}
</script>
@endsection
