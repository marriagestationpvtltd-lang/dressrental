@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 py-12 section-violet">
    <div class="w-full max-w-md">

        <!-- Logo & Heading -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 gradient-bg rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-glow-primary">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Create Account</h1>
            <p class="text-gray-500 mt-1.5 text-sm">Join DressRental Nepal and start renting today</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-violet-100 overflow-hidden">
            <div class="h-1.5 gradient-bg"></div>

            <form method="POST" action="{{ route('register') }}" class="p-8">
                @csrf
                <div class="space-y-5">

                    @php
                        $fields = [
                            ['type' => 'text', 'name' => 'name', 'label' => 'Full Name', 'required' => true, 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            ['type' => 'email', 'name' => 'email', 'label' => 'Email Address', 'required' => true, 'icon' => 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207'],
                            ['type' => 'tel', 'name' => 'phone', 'label' => 'Phone (optional)', 'required' => false, 'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                        ];
                    @endphp

                    @foreach($fields as $f)
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ $f['label'] }}</label>
                        <div class="relative">
                            <input type="{{ $f['type'] }}" name="{{ $f['name'] }}" value="{{ old($f['name']) }}" {{ $f['required'] ? 'required' : '' }} {{ $f['name'] === 'name' ? 'autofocus' : '' }}
                                   class="w-full border-2 {{ $errors->has($f['name']) ? 'border-rose-300 bg-rose-50' : 'border-gray-200 focus:border-primary-400' }} rounded-xl px-4 py-3 pl-11 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 {{ $errors->has($f['name']) ? 'text-rose-400' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        @error($f['name']) <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                    </div>
                    @endforeach

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" required
                                   class="w-full border-2 {{ $errors->has('password') ? 'border-rose-300 bg-rose-50' : 'border-gray-200 focus:border-primary-400' }} rounded-xl px-4 py-3 pl-11 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 {{ $errors->has('password') ? 'text-rose-400' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        @error('password') <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" required
                                   class="w-full border-2 border-gray-200 focus:border-primary-400 rounded-xl px-4 py-3 pl-11 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full gradient-bg text-white font-extrabold py-4 rounded-2xl hover:opacity-90 transition-opacity shadow-glow-primary text-base flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Create Free Account
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="px-8 flex items-center gap-3">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">OR</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <!-- Google Sign Up -->
            <div class="px-8 pb-6 pt-4">
                <a href="{{ route('auth.google') }}"
                   class="w-full flex items-center justify-center gap-3 border-2 border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-2xl transition-all text-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Sign up with Google
                </a>
            </div>

            <div class="px-8 pb-8 text-center border-t border-violet-50 pt-5">
                <p class="text-gray-500 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary-600 font-extrabold hover:text-primary-700 transition-colors ml-1">Sign In →</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
