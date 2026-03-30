@extends('layouts.admin')
@section('title', 'Add Page')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.pages.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add Page</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.pages.store') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none"
                           placeholder="e.g. Privacy Policy">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-400 shrink-0">/pages/</span>
                        <input type="text" name="slug" value="{{ old('slug') }}"
                               class="flex-1 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none font-mono text-sm"
                               placeholder="privacy-policy (auto-generated if left empty)">
                    </div>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                    <textarea name="content" rows="16" required
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none resize-y font-mono text-sm"
                              placeholder="Write page content here. HTML is supported.">{{ old('content') }}</textarea>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none bg-white">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-primary-600 text-white font-bold py-3 rounded-2xl hover:bg-primary-700">Save Page</button>
                <a href="{{ route('admin.pages.index') }}" class="flex-1 border border-gray-200 text-gray-700 font-medium py-3 rounded-2xl text-center hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
