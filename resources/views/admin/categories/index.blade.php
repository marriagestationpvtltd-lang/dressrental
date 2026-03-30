@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dress Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-primary-700 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Category
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Category</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Dresses</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($categories as $cat)
            {{-- Parent category row --}}
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-lg">{{ $cat->icon ?: '👗' }}</div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $cat->name }}</div>
                            <div class="text-xs text-gray-500">{{ $cat->slug }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-sm font-bold text-primary-600">{{ $cat->dresses_count }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $cat->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.categories.ornaments.manage', $cat) }}" class="text-fuchsia-500 hover:text-fuchsia-700 text-sm font-medium">Ornaments</a>
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            {{-- Subcategory rows --}}
            @foreach($cat->subcategories as $sub)
            <tr class="hover:bg-violet-50 bg-violet-50/30">
                <td class="px-5 py-2.5">
                    <div class="flex items-center gap-3 pl-6">
                        <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <div class="w-7 h-7 bg-violet-100 rounded-lg flex items-center justify-center text-base">{{ $sub->icon ?: '👗' }}</div>
                        <div>
                            <div class="font-medium text-gray-700 text-sm">{{ $sub->name }}</div>
                            <div class="text-xs text-gray-400">{{ $sub->slug }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-2.5 text-sm font-bold text-violet-600">{{ $sub->dresses_count }}</td>
                <td class="px-5 py-2.5">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sub->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $sub->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-5 py-2.5 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.categories.ornaments.manage', $sub) }}" class="text-fuchsia-500 hover:text-fuchsia-700 text-sm font-medium">Ornaments</a>
                        <a href="{{ route('admin.categories.edit', $sub) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $sub) }}" onsubmit="return confirm('Delete this subcategory?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            @empty
            <tr><td colspan="4" class="text-center py-12 text-gray-400">No categories found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
