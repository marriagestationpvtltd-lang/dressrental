@extends('layouts.app')
@section('title', 'My Profile')

@section('content')

<!-- Header -->
<div class="gradient-hero text-white">
    <div class="max-w-2xl mx-auto px-4 py-10">
        <h1 class="text-2xl md:text-3xl font-extrabold">My Profile</h1>
        <p class="text-violet-300 text-sm mt-1">Manage your personal information</p>
    </div>
    <div class="h-6 overflow-hidden">
        <svg viewBox="0 0 1440 24" class="w-full" preserveAspectRatio="none" fill="#f9fafb">
            <path d="M0,24 C360,0 1080,0 1440,24 L1440,24 L0,24 Z"/>
        </svg>
    </div>
</div>

<div class="bg-gray-50 min-h-screen pb-12">
    <div class="max-w-2xl mx-auto px-4 -mt-4">

        <div class="bg-white rounded-3xl shadow-xl border border-violet-100 overflow-hidden">
            <!-- Profile Header -->
            <div class="gradient-bg p-8 text-center relative overflow-hidden">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-rose-500/20 rounded-full"></div>
                <div class="relative">
                    <div class="inline-block relative">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-2xl object-cover border-4 border-white/50 shadow-xl">
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-400 border-2 border-white rounded-full shadow-sm"></div>
                    </div>
                    <h2 class="text-xl font-extrabold text-white mt-4">{{ $user->name }}</h2>
                    <p class="text-violet-200 text-sm mt-0.5">{{ $user->email }}</p>
                    <span class="inline-block bg-white/20 border border-white/30 text-white text-xs font-bold px-3 py-1 rounded-full mt-2">
                        Member since {{ $user->created_at->format('M Y') }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-7">
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    <!-- Photo upload -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Profile Photo</label>
                        <div class="border-2 border-dashed border-violet-200 rounded-xl p-4 bg-violet-50/50 hover:border-primary-400 transition-colors">
                            <input type="file" name="profile_photo" accept="image/*"
                                   class="w-full text-sm text-gray-600 file:mr-3 file:border-0 file:bg-primary-600 file:text-white file:px-4 file:py-2 file:rounded-xl file:text-xs file:font-bold hover:file:bg-primary-700 file:transition-colors cursor-pointer">
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="h-px bg-gradient-to-r from-transparent via-violet-200 to-transparent"></div>

                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full Name</label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full border-2 border-gray-200 focus:border-primary-400 rounded-xl px-4 py-3 pl-11 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>

                    <!-- Email (disabled) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 pl-11 bg-gray-50 text-gray-400 text-sm cursor-not-allowed">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Email cannot be changed</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone Number</label>
                        <div class="relative">
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full border-2 border-gray-200 focus:border-primary-400 rounded-xl px-4 py-3 pl-11 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full border-2 border-gray-200 focus:border-primary-400 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-200 outline-none resize-none transition-all text-sm">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <!-- Divider -->
                    <div class="h-px bg-gradient-to-r from-transparent via-rose-200 to-transparent"></div>

                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4">
                        <h3 class="font-bold text-rose-700 text-sm mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Change Password
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">New Password <span class="font-normal text-gray-400">(leave blank to keep)</span></label>
                                <input type="password" name="password"
                                       class="w-full border-2 border-rose-200 focus:border-rose-400 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-rose-200 outline-none transition-all text-sm bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full border-2 border-rose-200 focus:border-rose-400 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-rose-200 outline-none transition-all text-sm bg-white">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full gradient-bg text-white font-extrabold py-4 rounded-2xl hover:opacity-90 transition-opacity shadow-glow-primary flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
