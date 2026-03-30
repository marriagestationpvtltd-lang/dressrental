@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Users</h1>
</div>

<form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
           class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none flex-1">
    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-primary-700">Search</button>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Phone</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Bookings</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Joined</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->profile_photo_url }}" class="w-8 h-8 rounded-full object-cover" alt="">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-sm text-gray-600 hidden md:table-cell">
                    @if($user->phone)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $user->phone) }}" target="_blank"
                           class="inline-flex items-center gap-1 text-green-600 hover:text-green-700">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.116.55 4.102 1.514 5.834L0 24l6.334-1.482A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.031-1.384l-.361-.214-3.741.875.909-3.63-.236-.374A9.793 9.793 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                            {{ $user->phone }}
                        </a>
                    @else
                        —
                    @endif
                </td>
                <td class="px-5 py-3 text-sm font-bold text-primary-600">{{ $user->bookings_count }}</td>
                <td class="px-5 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-5 py-3 text-right">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-gray-400">No users found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
