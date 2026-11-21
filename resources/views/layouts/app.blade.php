
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 text-slate-800 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BD JobFinder - Smart Bangladesh Job Aggregator')</title>
    <meta name="description" content="@yield('meta_description', 'Aggregating ongoing private, government, and NGO job circulars across Bangladesh with duplicate-safe scraping.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Top Announcement Banner -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse mr-1.5"></span> Live Circulars Active
                </span>
                <span class="text-slate-300 font-medium">Aggregating verified employment opportunities across Bangladesh</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="hidden md:inline text-slate-400">Verified Portals: Bdjobs, Teletalk, Skill.jobs & LinkedIn</span>
                <a href="{{ route('jobs.govt') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold transition flex items-center gap-1">
                    <span>Govt. & Bank Hub</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('jobs.index') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xl font-extrabold tracking-tight text-slate-900">BD<span class="text-blue-600">JobFinder</span></span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">BD</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium leading-none -mt-0.5">Aggregator Portal</p>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('jobs.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('jobs.index') && !request()->routeIs('jobs.govt') ? 'text-blue-600 bg-blue-50' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-50' }}">
                            Job Explorer
                        </a>
                        <a href="{{ route('jobs.govt') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('jobs.govt') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/50' }}">
                            <span>Govt. & Bank Hub</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-emerald-600 text-white">Govt</span>
                        </a>
                    </nav>
                </div>

                <!-- Right Nav: Guest vs User vs Admin -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-500 text-white hover:bg-amber-600 shadow-xs transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                <span>Scraper Admin</span>
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-blue-600 hover:bg-slate-100 transition {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-blue-600' : '' }}">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            <span>My Dashboard</span>
                        </a>

                        <div class="h-5 w-px bg-slate-200"></div>

                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs uppercase border border-blue-200">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <span class="text-xs font-semibold text-slate-700 hidden lg:inline max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Sign Out" class="text-xs text-slate-500 hover:text-red-600 p-1.5 rounded hover:bg-slate-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-600/30 transition">
                            Create Account
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="p-2 text-slate-600 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-blue-600 px-2 py-1">Sign In</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile sub-bar -->
        <div class="md:hidden flex items-center justify-around py-2 border-t border-slate-100 bg-slate-50 text-xs font-semibold">
            <a href="{{ route('jobs.index') }}" class="px-3 py-1.5 {{ request()->routeIs('jobs.index') && !request()->routeIs('jobs.govt') ? 'text-blue-600 font-bold' : 'text-slate-600' }}">All Jobs</a>
            <a href="{{ route('jobs.govt') }}" class="px-3 py-1.5 text-emerald-700 font-bold flex items-center gap-1">Govt. Hub</a>
            @auth
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-bold' : 'text-slate-600' }}">Dashboard</a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 text-amber-700 font-bold">Admin</a>
                @endif
            @endauth
        </div>
    </header>

    <!-- Flash Messages / Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-900 shadow-xs">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 shadow-xs">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1 text-sm font-medium">{{ session('info') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Page Content Slot -->
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="mt-16 bg-slate-900 text-slate-300 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <!-- Col 1: About & Concept -->
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">BD</div>
                        <span class="text-xl font-bold text-white">BD JobFinder</span>
                        <span class="text-xs bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2 py-0.5 rounded font-mono">Aggregator</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-md">
                        Bangladesh's premier job aggregator platform. We continuously fetch, normalize, and verify ongoing employment opportunities from Bdjobs, Teletalk, Skill.jobs, and LinkedIn without allowing direct employer postings.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-slate-400 pt-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Real-time link validation & ongoing circular verification</span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-xs font-bold text-slate-100 uppercase tracking-wider mb-3">Quick Navigation</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition">All Ongoing Jobs</a></li>
                        <li><a href="{{ route('jobs.govt') }}" class="hover:text-emerald-400 transition font-medium">Govt & Bank Circulars</a></li>
                        <li><a href="{{ route('jobs.index', ['category' => 'remote']) }}" class="hover:text-white transition">Remote / Global BD Jobs</a></li>
                        <li><a href="{{ route('jobs.index', ['ending_soon' => 1]) }}" class="hover:text-amber-400 transition">Ending in 3 Days</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal Coverage -->
                <div>
                    <h4 class="text-xs font-bold text-slate-100 uppercase tracking-wider mb-3">Aggregated Sources</h4>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-1 rounded bg-slate-800 text-xs text-slate-300 border border-slate-700">Bdjobs.com</span>
                        <span class="px-2 py-1 rounded bg-slate-800 text-xs text-emerald-400 border border-emerald-900/50">Teletalk Govt</span>
                        <span class="px-2 py-1 rounded bg-slate-800 text-xs text-cyan-300 border border-slate-700">Skill.jobs</span>
                        <span class="px-2 py-1 rounded bg-slate-800 text-xs text-sky-400 border border-slate-700">LinkedIn BD</span>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-800 text-xs text-slate-500">
                        All circulars are verified for active deadlines and live official application links.
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Specs -->
            <div class="pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3">
                <p>&copy; {{ date('Y') }} BD JobFinder. All rights reserved. The Smart Job Aggregator for Bangladesh.</p>
                <div class="flex items-center gap-4">
                    <span>Verified Official Sources</span>
                    <span>•</span>
                    <span>Bangladesh</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
