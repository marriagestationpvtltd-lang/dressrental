@extends('layouts.admin')
@section('title', 'Add Banner')

@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.banners.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add Banner</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data"
              x-data="{ mediaType: '{{ old('media_type', 'image') }}' }">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="e.g. Summer Collection"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Media Type *</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="media_type" value="image"
                                   x-model="mediaType"
                                   class="text-primary-600 border-gray-300">
                            <span class="text-sm font-medium text-gray-700">Image / GIF</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="media_type" value="youtube"
                                   x-model="mediaType"
                                   class="text-primary-600 border-gray-300">
                            <span class="text-sm font-medium text-gray-700">YouTube Video</span>
                        </label>
                    </div>
                    @error('media_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Image upload --}}
                <div x-show="mediaType === 'image'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image / GIF File *</label>
                    <input type="file" name="image" accept="image/*,.gif"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none text-sm">
                    <p class="text-xs text-gray-400 mt-1">Supports JPG, PNG, GIF, WebP. Max 10 MB.</p>
                    @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- YouTube URL --}}
                <div x-show="mediaType === 'youtube'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL or Video ID *</label>
                    <input type="text" name="youtube_url" value="{{ old('youtube_url') }}"
                           placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Paste the full YouTube URL or just the 11-character video ID.</p>
                    @error('youtube_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-4 h-4 rounded border-gray-300 text-primary-600">
                    <span class="text-sm font-medium text-gray-700">Active (visible on homepage)</span>
                </label>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-primary-600 text-white font-bold py-3 rounded-2xl hover:bg-primary-700">Save</button>
                <a href="{{ route('admin.banners.index') }}" class="flex-1 border border-gray-200 text-gray-700 font-medium py-3 rounded-2xl text-center hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
