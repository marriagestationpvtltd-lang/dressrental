@extends('layouts.admin')
@section('title', 'Pages')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Static Pages</h1>
    <a href="{{ route('admin.pages.create') }}" class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-primary-700 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Page
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Title</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Slug / URL</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pages as $page)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-900">{{ $page->title }}</div>
                </td>
                <td class="px-5 py-3">
                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank"
                       class="text-primary-600 hover:underline text-sm font-mono">/pages/{{ $page->slug }}</a>
                </td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $page->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($page->status) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-12 text-gray-400">No pages found. Create your first page!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
