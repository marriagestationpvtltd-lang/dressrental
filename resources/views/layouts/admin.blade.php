<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - DressRental Nepal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 300:'#c4b5fd', 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6', 900:'#4c1d95' },
                        rose:    { 50:'#fff1f2', 100:'#ffe4e6', 400:'#fb7185', 500:'#f43f5e', 600:'#e11d48' },
                        amber:   { 50:'#fffbeb', 100:'#fef3c7', 400:'#fbbf24', 500:'#f59e0b', 600:'#d97706' },
                        emerald: { 50:'#ecfdf5', 100:'#d1fae5', 500:'#10b981', 600:'#059669' },
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .gradient-bg    { background: linear-gradient(135deg, #6d28d9 0%, #db2777 100%); }
        .gradient-gold  { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
        .sidebar-item-active { background: linear-gradient(135deg, rgba(124,58,237,.25), rgba(219,39,119,.15)); border-left: 3px solid #a78bfa; color: #fff; }
        .sidebar-item   { border-left: 3px solid transparent; }
        ::-webkit-scrollbar { width: 5px; } ::-webkit-scrollbar-track { background: #1e1b4b; } ::-webkit-scrollbar-thumb { background: #4c1d95; border-radius: 3px; }
    </style>
</head>
<body class="text-gray-800" x-data="{ sidebarOpen: true }" style="background:#f1f5f9;">

<div class="flex h-screen overflow-hidden">

    <!-- ── Sidebar ── -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'" class="flex flex-col transition-all duration-300 shrink-0 overflow-hidden"
           style="background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);">

        <!-- Brand header -->
        <div class="flex items-center gap-3 px-4 h-16 border-b border-white/10 shrink-0">
            <div class="w-9 h-9 gradient-bg rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l9 7-9 7-9-7 9-7z"/></svg>
            </div>
            <div x-show="sidebarOpen" x-transition>
                <div class="font-extrabold text-white text-sm leading-tight">DressRental</div>
                <div class="text-xs text-violet-400 font-semibold">Admin Panel</div>
            </div>
        </div>

        <!-- Nav items -->
        <nav class="flex-1 py-4 overflow-y-auto px-2">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard',       'label' => 'Dashboard',  'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => 'text-violet-400'],
                    ['route' => 'admin.dresses.index',   'label' => 'Dresses',    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'color' => 'text-pink-400'],
                    ['route' => 'admin.bookings.index',  'label' => 'Bookings',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'text-emerald-400'],
                    ['route' => 'admin.payments.index',  'label' => 'Payments',   'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'text-amber-400'],
                    ['route' => 'admin.users.index',     'label' => 'Users',      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'text-sky-400'],
                    ['route' => 'admin.categories.index','label' => 'Categories', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'color' => 'text-rose-400'],
                    ['route' => 'admin.settings.index',  'label' => 'Settings',  'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'text-gray-400'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || request()->routeIs(str_replace('.index','',$item['route']).'.*');
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 mx-1 mb-1 rounded-xl transition-all sidebar-item {{ $isActive ? 'sidebar-item-active' : 'text-gray-400 hover:bg-white/8 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-violet-300' : $item['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold truncate">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Bottom section -->
        <div class="p-3 border-t border-white/10 space-y-1 shrink-0">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-white/8 hover:text-white transition-all">
                <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span x-show="sidebarOpen" class="text-sm font-semibold">View Site</span>
            </a>
        </div>
    </aside>

    <!-- ── Main ── -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top bar -->
        <header class="bg-white h-16 flex items-center px-6 justify-between shrink-0 border-b border-gray-200 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="w-9 h-9 rounded-xl border border-gray-200 bg-gray-50 hover:bg-primary-50 hover:border-primary-300 flex items-center justify-center text-gray-500 hover:text-primary-600 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <!-- Breadcrumb / page title from parent -->
                <div class="text-sm font-semibold text-gray-500 hidden md:block">
                    @yield('page_title', 'Admin Dashboard')
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5">
                    <div class="w-6 h-6 gradient-bg rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 border border-rose-200 px-3 py-2 rounded-xl hover:bg-rose-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash messages -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mx-6 mt-4 bg-emerald-50 border-2 border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="font-semibold text-sm">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="w-6 h-6 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-700 text-xs hover:bg-emerald-300 transition-colors">✕</button>
            </div>
        @endif
        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" class="mx-6 mt-4 bg-rose-50 border-2 border-rose-200 text-rose-800 px-5 py-3 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li class="text-sm font-medium">{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
