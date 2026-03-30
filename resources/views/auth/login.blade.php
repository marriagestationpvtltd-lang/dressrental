@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 py-12 section-mixed">
    <div class="w-full max-w-md">

        <!-- Logo & Heading -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 gradient-bg rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-glow-primary">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Welcome Back!</h1>
            <p class="text-gray-500 mt-1.5 text-sm">Sign in to your account to continue</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-violet-100 overflow-hidden">
            <!-- Card top accent -->
            <div class="h-1.5 gradient-bg"></div>

            <form method="POST" action="{{ route('login') }}" class="p-8">
                @csrf
                <div class="space-y-5">

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full border-2 {{ $errors->has('email') ? 'border-rose-300 bg-rose-50' : 'border-gray-200 focus:border-primary-400' }} rounded-xl px-4 py-3 pl-11 focus:ring-2 focus:ring-primary-200 focus:border-primary-400 outline-none transition-all text-sm">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 {{ $errors->has('email') ? 'text-rose-400' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        @error('email') <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" required
                                   class="w-full border-2 border-gray-200 focus:border-primary-400 rounded-xl px-4 py-3 pl-11 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Remember me</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full gradient-bg text-white font-extrabold py-4 rounded-2xl hover:opacity-90 transition-opacity shadow-glow-primary text-base flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Sign In
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="px-8 flex items-center gap-3">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">OR</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <!-- Google Login -->
            <div class="px-8 pb-6 pt-4">
                <a href="{{ route('auth.google') }}"
                   class="w-full flex items-center justify-center gap-3 border-2 border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-2xl transition-all text-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </a>
            </div>

            <!-- Footer -->
            <div class="px-8 pb-8 text-center border-t border-violet-50 pt-5">
                <p class="text-gray-500 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary-600 font-extrabold hover:text-primary-700 transition-colors ml-1">Create one →</a>
                </p>
            </div>
        </div>

        <!-- Trust badges -->
        <div class="flex items-center justify-center gap-6 mt-6">
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Secure Login
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                500+ Dresses
            </div>
        </div>
    </div>
</div>
@endsection
