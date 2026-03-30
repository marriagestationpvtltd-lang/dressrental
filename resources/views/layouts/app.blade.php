<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DressRental Nepal') - Rent Premium Dresses</title>
    <meta name="description" content="@yield('meta_description', setting('meta_description', 'Rent premium dresses in Nepal. Bikram Sambat calendar, easy booking, eSewa & Khalti payments.'))">
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
                        rose:    { 50:'#fff1f2', 100:'#ffe4e6', 200:'#fecdd3', 400:'#fb7185', 500:'#f43f5e', 600:'#e11d48', 700:'#be123c' },
                        amber:   { 50:'#fffbeb', 100:'#fef3c7', 300:'#fcd34d', 400:'#fbbf24', 500:'#f59e0b', 600:'#d97706', 700:'#b45309' },
                        emerald: { 50:'#ecfdf5', 100:'#d1fae5', 400:'#34d399', 500:'#10b981', 600:'#059669', 700:'#047857' },
                        sky:     { 50:'#f0f9ff', 100:'#e0f2fe', 400:'#38bdf8', 500:'#0ea5e9', 600:'#0284c7' },
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    boxShadow: {
                        'glow-primary': '0 0 20px rgba(124,58,237,.35)',
                        'glow-rose':    '0 0 20px rgba(244,63,94,.35)',
                        'card':         '0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -2px rgba(0,0,0,.05)',
                        'card-hover':   '0 20px 40px rgba(0,0,0,.12)',
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --clr-primary: #7c3aed;
            --clr-primary-dark: #6d28d9;
            --clr-rose: #f43f5e;
            --clr-gold: #f59e0b;
            --clr-emerald: #10b981;
        }
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* ── Gradient utilities ──────────────────────── */
        .gradient-bg        { background: linear-gradient(135deg, #6d28d9 0%, #db2777 100%); }
        .gradient-hero      { background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 40%, #be123c 100%); }
        .gradient-gold      { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
        .gradient-emerald   { background: linear-gradient(135deg, #047857 0%, #10b981 100%); }
        .gradient-rose      { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); }
        .gradient-text      { background: linear-gradient(135deg, #7c3aed, #db2777); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        /* ── Card hover ──────────────────────────────── */
        .card-hover { transition: transform .22s ease, box-shadow .22s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,.13); }

        /* ── Bottom nav safe area ────────────────────── */
        .bottom-nav-safe { padding-bottom: calc(4rem + env(safe-area-inset-bottom)); }

        /* ── Section backgrounds ─────────────────────── */
        .section-light    { background: #fafafa; }
        .section-violet   { background: linear-gradient(160deg, #f5f3ff 0%, #ede9fe 100%); }
        .section-rose     { background: linear-gradient(160deg, #fff1f2 0%, #ffe4e6 100%); }
        .section-amber    { background: linear-gradient(160deg, #fffbeb 0%, #fef3c7 100%); }
        .section-mixed    { background: linear-gradient(160deg, #f5f3ff 0%, #fff1f2 100%); }

        /* ── Navbar active indicator ─────────────────── */
        .nav-active { color: #7c3aed; position: relative; }
        .nav-active::after { content: ''; position: absolute; bottom: -4px; left: 0; right: 0; height: 2px; background: linear-gradient(135deg,#7c3aed,#db2777); border-radius: 2px; }

        /* ── Toast animations ────────────────────────── */
        [x-show] { transition: opacity .3s ease; }

        /* ── Scrollbar ───────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c4b5fd; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #7c3aed; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-800 bottom-nav-safe">

    <!-- Top Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-violet-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 gradient-bg rounded-xl flex items-center justify-center shadow-glow-primary">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                    </div>
                    <span class="font-extrabold text-lg text-gray-900">DressRental<span class="gradient-text">Nepal</span></span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-7">
                    <a href="{{ route('home') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'nav-active text-primary-600' : 'text-gray-600 hover:text-primary-600' }}">Home</a>
                    <a href="{{ route('dresses.index') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('dresses.*') ? 'nav-active text-primary-600' : 'text-gray-600 hover:text-primary-600' }}">Dresses</a>
                    @auth
                        <a href="{{ route('bookings.index') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('bookings.*') ? 'nav-active text-primary-600' : 'text-gray-600 hover:text-primary-600' }}">My Bookings</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Admin
                            </a>
                        @endif
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 text-gray-700 hover:text-primary-600 transition-colors">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover ring-2 ring-primary-200">
                                <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-xl py-2 border border-violet-100 overflow-hidden">
                                <div class="px-4 py-2 border-b border-violet-50">
                                    <p class="text-xs text-gray-400">Signed in as</p>
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-violet-50 hover:text-primary-700 transition-colors">
                                    <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-violet-50 hover:text-primary-700 transition-colors">
                                    <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                                <div class="border-t border-rose-50 mt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-primary-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="gradient-bg text-white px-5 py-2 rounded-xl text-sm font-bold hover:opacity-90 transition-opacity shadow-sm">
                            Get Started
                        </a>
                    @endauth
                </div>

                <!-- Mobile: logo + auth icon -->
                <div class="flex md:hidden items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover ring-2 ring-primary-300">
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-primary-600 font-semibold text-sm">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-cloak x-transition
             class="fixed top-20 right-4 z-50 bg-emerald-500 text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 max-w-sm border border-emerald-400">
            <div class="w-8 h-8 bg-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="font-medium text-sm">{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto w-6 h-6 rounded-full bg-emerald-400 flex items-center justify-center opacity-80 hover:opacity-100 text-xs">✕</button>
        </div>
    @endif
    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" x-cloak x-transition
             class="fixed top-20 right-4 z-50 bg-rose-500 text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-start gap-3 max-w-sm border border-rose-400">
            <div class="w-8 h-8 bg-rose-400 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1">@foreach($errors->all() as $e)<p class="text-sm font-medium">{{ $e }}</p>@endforeach</div>
            <button @click="show = false" class="w-6 h-6 rounded-full bg-rose-400 flex items-center justify-center opacity-80 hover:opacity-100 text-xs shrink-0">✕</button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="hidden md:block mt-16" style="background: linear-gradient(180deg,#1e1b4b 0%,#0f172a 100%);">
        <!-- Top accent border -->
        <div class="h-1 gradient-bg"></div>
        <div class="max-w-7xl mx-auto px-4 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 gradient-bg rounded-xl flex items-center justify-center shadow-glow-primary">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
                        </div>
                        <span class="font-extrabold text-white text-lg">DressRental Nepal</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ setting('site_tagline', 'Premium dress rentals with Nepali calendar booking & digital payments. Look stunning at every event.') }}</p>
                    <div class="flex gap-2 mt-5">
                        @if(setting('social_facebook'))
                        <a href="{{ setting('social_facebook') }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        @else
                        <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center hover:bg-primary-600 transition-colors cursor-pointer">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </div>
                        @endif
                        @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center hover:bg-rose-500 transition-colors">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 3.243.145 4.762 1.69 4.907 4.907.048 1.024.061 1.378.061 3.808s-.013 2.784-.061 3.808c-.145 3.217-1.664 4.762-4.907 4.907-1.024.048-1.377.061-3.808.061-2.43 0-2.784-.013-3.808-.061-3.243-.145-4.762-1.69-4.907-4.907C2.013 14.784 2 14.43 2 12s.013-2.784.061-3.808C2.206 4.69 3.725 3.145 6.968 3 7.992 2.953 8.346 2.94 10.777 2.94l1.538.06zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-9.87a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z"/></svg>
                        </a>
                        @else
                        <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center hover:bg-rose-500 transition-colors cursor-pointer">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 3.243.145 4.762 1.69 4.907 4.907.048 1.024.061 1.378.061 3.808s-.013 2.784-.061 3.808c-.145 3.217-1.664 4.762-4.907 4.907-1.024.048-1.377.061-3.808.061-2.43 0-2.784-.013-3.808-.061-3.243-.145-4.762-1.69-4.907-4.907C2.013 14.784 2 14.43 2 12s.013-2.784.061-3.808C2.206 4.69 3.725 3.145 6.968 3 7.992 2.953 8.346 2.94 10.777 2.94l1.538.06zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-9.87a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z"/></svg>
                        </div>
                        @endif
                        @if(setting('social_whatsapp'))
                        <a href="https://wa.me/{{ setting('social_whatsapp') }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="w-4 h-0.5 gradient-bg rounded"></span>
                        Quick Links
                    </h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('dresses.index') }}" class="text-gray-400 hover:text-white text-sm transition-colors flex items-center gap-1.5"><span class="text-primary-400">›</span> Browse Dresses</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white text-sm transition-colors flex items-center gap-1.5"><span class="text-primary-400">›</span> Create Account</a></li>
                        @auth
                        <li><a href="{{ route('bookings.index') }}" class="text-gray-400 hover:text-white text-sm transition-colors flex items-center gap-1.5"><span class="text-primary-400">›</span> My Bookings</a></li>
                        @endauth
                        <li><a href="{{ route('pages.show', 'privacy-policy') }}" class="text-gray-400 hover:text-white text-sm transition-colors flex items-center gap-1.5"><span class="text-primary-400">›</span> Privacy Policy</a></li>
                        <li><a href="{{ route('pages.show', 'terms-and-conditions') }}" class="text-gray-400 hover:text-white text-sm transition-colors flex items-center gap-1.5"><span class="text-primary-400">›</span> Terms &amp; Conditions</a></li>
                    </ul>
                </div>

                <!-- Payment -->
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="w-4 h-0.5 gradient-gold rounded"></span>
                        Payments
                    </h4>
                    <ul class="space-y-2.5">
                        <li class="flex items-center gap-2 text-sm text-gray-400">
                            <span class="w-5 h-5 bg-emerald-500/20 border border-emerald-500/30 rounded flex items-center justify-center text-emerald-400 text-xs font-bold">e</span>
                            eSewa
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-400">
                            <span class="w-5 h-5 bg-violet-500/20 border border-violet-500/30 rounded flex items-center justify-center text-violet-400 text-xs font-bold">K</span>
                            Khalti
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-400">
                            <span class="w-5 h-5 bg-amber-500/20 border border-amber-500/30 rounded flex items-center justify-center text-amber-400 text-xs font-bold">₨</span>
                            Cash on Delivery
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="w-4 h-0.5 gradient-rose rounded"></span>
                        Contact
                    </h4>
                    <ul class="space-y-2.5">
                        @if(setting('contact_address'))
                        <li class="flex items-start gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4 text-rose-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ setting('contact_address') }}
                        </li>
                        @else
                        <li class="flex items-start gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4 text-rose-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Kathmandu, Nepal
                        </li>
                        @endif
                        @if(setting('contact_phone'))
                        <li class="flex items-start gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4 text-emerald-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ setting('contact_phone') }}" class="hover:text-white transition-colors">{{ setting('contact_phone') }}</a>
                        </li>
                        @endif
                        @if(setting('contact_email'))
                        <li class="flex items-start gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4 text-amber-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ setting('contact_email') }}" class="hover:text-white transition-colors">{{ setting('contact_email') }}</a>
                        </li>
                        @endif
                        @if(setting('social_whatsapp'))
                        <li class="flex items-start gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4 text-emerald-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <a href="https://wa.me/{{ setting('social_whatsapp') }}" target="_blank" rel="noopener" class="hover:text-white transition-colors">WhatsApp</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Bottom bar -->
                <div class="flex items-center justify-center gap-6 mt-6 border-t border-white/10 pt-4">
                <p class="text-gray-500 text-sm">© {{ date('Y') }} {{ setting('site_name', 'DressRental Nepal') }}. All rights reserved.</p>
                <div class="flex items-center gap-1 text-xs text-gray-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse inline-block" aria-hidden="true"></span>
                    <span class="sr-only">System status:</span>
                    All systems operational
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-violet-100 z-40 md:hidden shadow-lg" style="padding-bottom: env(safe-area-inset-bottom);">
        <div class="grid grid-cols-4 h-16">
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('home') ? 'text-primary-600' : 'text-gray-400 hover:text-primary-500' }}">
                @if(request()->routeIs('home'))
                    <div class="w-10 h-6 gradient-bg rounded-full flex items-center justify-center mb-0.5">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                @else
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                @endif
                <span class="text-xs font-semibold">Home</span>
            </a>
            <a href="{{ route('dresses.index') }}" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('dresses.*') ? 'text-primary-600' : 'text-gray-400 hover:text-primary-500' }}">
                @if(request()->routeIs('dresses.*'))
                    <div class="w-10 h-6 gradient-bg rounded-full flex items-center justify-center mb-0.5">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
                    </div>
                @else
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                @endif
                <span class="text-xs font-semibold">Dresses</span>
            </a>
            <a href="{{ auth()->check() ? route('bookings.index') : route('login') }}" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('bookings.*') ? 'text-primary-600' : 'text-gray-400 hover:text-primary-500' }}">
                @if(request()->routeIs('bookings.*'))
                    <div class="w-10 h-6 gradient-bg rounded-full flex items-center justify-center mb-0.5">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                @else
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                @endif
                <span class="text-xs font-semibold">Bookings</span>
            </a>
            <a href="{{ auth()->check() ? route('profile') : route('login') }}" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('profile*') || request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-400 hover:text-primary-500' }}">
                @if(request()->routeIs('profile*') || request()->routeIs('dashboard'))
                    <div class="w-10 h-6 gradient-bg rounded-full flex items-center justify-center mb-0.5">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/></svg>
                    </div>
                @else
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                @endif
                <span class="text-xs font-semibold">Profile</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
