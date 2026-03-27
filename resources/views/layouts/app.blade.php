<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DressRental Nepal') - Rent Premium Dresses</title>
    <meta name="description" content="@yield('meta_description', 'Rent premium dresses in Nepal. Bikram Sambat calendar, easy booking, eSewa & Khalti payments.')">
    <meta name="theme-color" content="#6d28d9">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 300:'#c4b5fd', 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6', 900:'#4c1d95' },
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .gradient-bg { background: linear-gradient(135deg, #6d28d9 0%, #db2777 100%); }
        .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,.15); }
        .bottom-nav-safe { padding-bottom: calc(4rem + env(safe-area-inset-bottom)); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-800 bottom-nav-safe">

    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                    </div>
                    <span class="font-bold text-lg text-gray-900">DressRental<span class="text-primary-600">Nepal</span></span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Home</a>
                    <a href="{{ route('dresses.index') }}" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Dresses</a>
                    @auth
                        <a href="{{ route('bookings.index') }}" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">My Bookings</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Admin</a>
                        @endif
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 text-gray-700 hover:text-primary-600">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors">Register</a>
                    @endauth
                </div>

                <!-- Mobile: just logo + auth -->
                <div class="flex md:hidden items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover">
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-primary-600 font-medium text-sm">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-cloak
             class="fixed top-20 right-4 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 max-w-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
        </div>
    @endif
    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" x-cloak
             class="fixed top-20 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg flex items-start gap-3 max-w-sm">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>@foreach($errors->all() as $e)<p class="text-sm">{{ $e }}</p>@endforeach</div>
            <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-16 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                        </div>
                        <span class="font-bold text-white">DressRental Nepal</span>
                    </div>
                    <p class="text-sm">Premium dress rentals with easy Nepali calendar booking & digital payments.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('dresses.index') }}" class="hover:text-white transition-colors">Browse Dresses</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Create Account</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Payment</h4>
                    <ul class="space-y-2 text-sm">
                        <li>eSewa</li>
                        <li>Khalti</li>
                        <li>Cash on Delivery</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Contact</h4>
                    <p class="text-sm">Nepal</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm">
                <p>© {{ date('Y') }} DressRental Nepal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 md:hidden" style="padding-bottom: env(safe-area-inset-bottom);">
        <div class="grid grid-cols-4 h-16">
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('home') ? 'text-primary-600' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-xs font-medium">Home</span>
            </a>
            <a href="{{ route('dresses.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('dresses.*') ? 'text-primary-600' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span class="text-xs font-medium">Dresses</span>
            </a>
            <a href="{{ auth()->check() ? route('bookings.index') : route('login') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('bookings.*') ? 'text-primary-600' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-xs font-medium">Bookings</span>
            </a>
            <a href="{{ auth()->check() ? route('profile') : route('login') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('profile*') || request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
