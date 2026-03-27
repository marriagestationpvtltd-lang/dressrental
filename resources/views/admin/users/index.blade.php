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
                <td class="px-5 py-3 text-sm text-gray-600 hidden md:table-cell">{{ $user->phone ?? '-' }}</td>
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
