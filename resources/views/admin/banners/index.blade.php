@extends('layouts.admin')
@section('title', 'Hero Banners')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Hero Banners</h1>
        <p class="text-sm text-gray-500 mt-1">Manage the homepage hero section media slider (images, GIFs, YouTube videos).</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-primary-700 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Banner
    </a>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($banners->isEmpty())
    <div class="px-6 py-16 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="font-medium">No banners yet.</p>
        <p class="text-xs mt-1">Add images, GIFs, or YouTube videos to build your hero slider.</p>
    </div>
    @else
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Preview</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Title / Type</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Order</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($banners as $banner)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    @if($banner->media_type === 'image')
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                             class="w-24 h-14 object-cover rounded-lg border border-gray-100">
                    @else
                        <div class="w-24 h-14 bg-red-50 border border-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
                        </div>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-900">{{ $banner->title ?: '—' }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">
                        @if($banner->media_type === 'youtube')
                            <span class="inline-flex items-center gap-1 text-red-600"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg> YouTube</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-blue-600"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Image / GIF</span>
                        @endif
                    </div>
                </td>
                <td class="px-5 py-3 text-sm font-medium text-gray-700">{{ $banner->sort_order }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Delete this banner?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<p class="text-xs text-gray-400 mt-4">Slider animation and interval can be adjusted in
    <a href="{{ route('admin.settings.index') }}" class="text-primary-600 hover:underline">Settings</a> (Hero Slider Animation, Hero Slider Interval).
</p>
@endsection
