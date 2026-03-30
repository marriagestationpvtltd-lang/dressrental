@extends('layouts.admin')
@section('title', 'Ornaments & Accessories')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Ornaments & Accessories</h1>
    <a href="{{ route('admin.ornaments.create') }}" class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-primary-700 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Ornament
    </a>
</div>

<!-- Filters -->
<form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ornaments..."
               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
    </div>
    <div class="min-w-40">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Category</label>
        <select name="category" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                    {{ \App\Models\Ornament::categoryLabel($cat) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="min-w-36">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
        <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
            <option value="">All Status</option>
            <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
            <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
    </div>
    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-900">Filter</button>
    @if(request()->hasAny(['search','category','status']))
        <a href="{{ route('admin.ornaments.index') }}" class="text-sm text-gray-500 hover:text-gray-700 py-2">Clear</a>
    @endif
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Ornament</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Category</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Price/Day</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($ornaments as $ornament)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-violet-50 border border-violet-100 shrink-0">
                            <img src="{{ $ornament->image_url }}" alt="{{ $ornament->name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $ornament->name }}</div>
                            <div class="text-xs text-gray-400">{{ $ornament->slug }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3">
                    <span class="bg-violet-100 text-violet-700 text-xs font-medium px-2.5 py-1 rounded-full">
                        {{ \App\Models\Ornament::categoryLabel($ornament->category) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-sm font-bold text-gray-800">₨{{ number_format($ornament->price_per_day) }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $ornament->status === 'available' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ ucfirst($ornament->status) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.ornaments.edit', $ornament) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.ornaments.destroy', $ornament) }}" onsubmit="return confirm('Delete this ornament?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-gray-400">No ornaments found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($ornaments->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $ornaments->links() }}
    </div>
    @endif
</div>
@endsection
