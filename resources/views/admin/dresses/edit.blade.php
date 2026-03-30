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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select name="category_id" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $dress->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Size *</label>
                    <select name="size" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                        @foreach($sizes as $size)
                            <option value="{{ $size }}" {{ old('size', $dress->size) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
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

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Add More Images</label>
                    <input type="file" id="dress-images" name="images[]" multiple accept="image/*"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none file:mr-3 file:border-0 file:bg-primary-50 file:text-primary-700 file:px-3 file:py-1 file:rounded-lg">
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
@endsection
