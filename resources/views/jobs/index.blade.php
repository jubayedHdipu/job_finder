@extends('layouts.app')

@section('title', 'BD JobFinder - Browse Verified Bangladesh Ongoing Jobs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Hero Header with Live Aggregator Stats -->
    <div class="mb-8 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 rounded-2xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30 mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Live Bangladesh Job Aggregator • No Direct Employer Posts</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-3">
                Discover Your Next Career in <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-300">Bangladesh</span>
            </h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-6">
                Directly synchronized from Bdjobs, Teletalk Govt Portals, Skill.jobs, and LinkedIn BD. Every circular is normalized, deduplicated, and verified active.
            </p>

            <!-- Quick Metrics Counter Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-3">
                    <div class="text-2xl font-black text-blue-400">{{ $totalOngoingCount }}</div>
                    <div class="text-xs text-slate-300 font-medium">Ongoing Live Jobs</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-3">
                    <div class="text-2xl font-black text-emerald-400">{{ $govtCount }}</div>
                    <div class="text-xs text-slate-300 font-medium">Govt & Bank Openings</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-3 col-span-2 sm:col-span-1">
                    <div class="text-2xl font-black text-amber-400">{{ $endingSoonCount }}</div>
                    <div class="text-xs text-slate-300 font-medium">Ending in 3 Days</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Search & Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-5 mb-8">
        <form method="GET" action="{{ route('jobs.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Keyword Search -->
            <div class="md:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Job title, company, or keywords..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Location Select -->
            <div class="md:col-span-3">
                <select name="location" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="All">All Locations (BD & Remote)</option>
                    @foreach($availableLocations as $loc)
                        @if($loc !== 'All')
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Source Portal Select -->
            <div class="md:col-span-2">
                <select name="source" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="All">All Sources</option>
                    @foreach($availableSources as $src)
                        @if($src !== 'All')
                            <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                    <span>Search</span>
                </button>
                @if(request()->hasAny(['q', 'location', 'source', 'category', 'experience', 'ending_soon', 'sort']))
                    <a href="{{ route('jobs.index') }}" title="Clear Filters" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>

            <!-- Hidden Inputs to Preserve Left Sidebar State on Search Bar Submission -->
            @if(request('category') && request('category') !== 'All')
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('experience') && request('experience') !== 'All')
                <input type="hidden" name="experience" value="{{ request('experience') }}">
            @endif
            @if(request('ending_soon'))
                <input type="hidden" name="ending_soon" value="1">
            @endif
        </form>
    </div>

    <!-- Main Two-Column Layout: Left Sidebar + Job Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Left Sidebar Filters -->
        <aside class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sticky top-20">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span>Filter Circulars</span>
                    </h3>
                    @if(request()->hasAny(['category', 'experience', 'ending_soon', 'location', 'source']))
                        <a href="{{ route('jobs.index', ['q' => request('q')]) }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">Reset</a>
                    @endif
                </div>

                <!-- Quick Filter: Ending in 3 Days -->
                <div class="py-4 border-b border-slate-100">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Urgency</h4>
                    <a href="{{ request()->fullUrlWithQuery(['ending_soon' => request('ending_soon') ? null : 1]) }}"
                       class="flex items-center justify-between p-2.5 rounded-xl border text-xs font-semibold transition {{ request('ending_soon') ? 'bg-amber-500 text-white border-amber-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-amber-50/80 hover:text-amber-800 hover:border-amber-300' }}">
                        <span class="flex items-center gap-2">
                            <span>⚡</span>
                            <span>Ending in 3 Days</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('ending_soon') ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                            {{ $endingSoonCount }}
                        </span>
                    </a>
                </div>

                <!-- Category Filters -->
                <div class="py-4 border-b border-slate-100">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Job Category</h4>
                    <div class="space-y-1.5">
                        @foreach($availableCategories as $catKey => $catLabel)
                            @php
                                $isActive = (request('category') == $catKey) || (!request('category') && $catKey === 'All');
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['category' => $catKey === 'All' ? null : $catKey]) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ $isActive ? 'bg-blue-50 text-blue-700 font-bold border border-blue-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span>{{ $catLabel }}</span>
                                @if($catKey === 'govt')
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                @elseif($catKey === 'remote')
                                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Experience Level Filters -->
                <div class="py-4 border-b border-slate-100">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Experience Level</h4>
                    <div class="space-y-1.5">
                        @foreach($availableExperiences as $expKey => $expLabel)
                            @php
                                $isActive = (request('experience') == $expKey) || (!request('experience') && $expKey === 'All');
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['experience' => $expKey === 'All' ? null : $expKey]) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition {{ $isActive ? 'bg-blue-50 text-blue-700 font-bold border border-blue-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span>{{ $expLabel }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Portal Aggregation Note -->
                <div class="pt-3 text-[11px] text-slate-500 leading-normal">
                    <div class="flex items-center gap-1.5 text-slate-700 font-semibold mb-1">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>Verified Job Listings</span>
                    </div>
                    Every circular is verified for active deadlines and live working links before appearing on the portal.
                </div>
            </div>
        </aside>

        <!-- Right Side: Job Cards Grid -->
        <main class="lg:col-span-3">

            <!-- Search Results Summary Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        @if(request('q'))
                            Search results for <span class="text-blue-600">"{{ request('q') }}"</span>
                        @elseif(request('category') && request('category') !== 'All')
                            <span class="capitalize">{{ request('category') }}</span> Jobs in Bangladesh
                        @else
                            All Ongoing Bangladesh Jobs
                        @endif
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">
                        Showing {{ $jobs->firstItem() ?? 0 }} - {{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} active verified listings
                    </p>
                </div>

                <!-- Sort Order Dropdown -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <label for="sort-select" class="text-xs font-semibold text-slate-500 whitespace-nowrap">Sort by:</label>
                    <select id="sort-select"
                            onchange="window.location.href = this.value"
                            class="py-1.5 px-3 rounded-lg border border-slate-200 text-xs font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Newest Published</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'deadline_asc']) }}" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>Deadline: Ending Soonest</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'deadline_desc']) }}" {{ request('sort') == 'deadline_desc' ? 'selected' : '' }}>Deadline: Furthest</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'title_asc']) }}" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Job Title (A-Z)</option>
                    </select>
                </div>
            </div>

            <!-- Job Cards List -->
            @if($jobs->count() > 0)
                <div class="space-y-4">
                    @foreach($jobs as $job)
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 relative group">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">

                                <!-- Job Information -->
                                <div class="flex-1 space-y-2">
                                    <!-- Badges Row -->
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <!-- Source Badge -->
                                        @if($job->source_name === 'Teletalk')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Teletalk Govt
                                            </span>
                                        @elseif($job->source_name === 'Bdjobs')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                Bdjobs
                                            </span>
                                        @elseif($job->source_name === 'LinkedIn')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                                LinkedIn
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                {{ $job->source_name }}
                                            </span>
                                        @endif

                                        <!-- Category Badge -->
                                        <span class="px-2 py-0.5 rounded-full font-semibold uppercase text-[10px] tracking-wider
                                            {{ $job->category === 'govt' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                            {{ $job->category === 'remote' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $job->category === 'ngo' ? 'bg-cyan-100 text-cyan-800' : '' }}
                                            {{ $job->category === 'private' ? 'bg-slate-100 text-slate-700' : '' }}">
                                            {{ $job->category }}
                                        </span>

                                        <!-- Experience Badge -->
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200 capitalize">
                                            {{ $job->experience_level }} level
                                        </span>

                                        @if($job->vacancy)
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $job->vacancy }} {{ Str::plural('Vacancy', $job->vacancy) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Job Title -->
                                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition">
                                        <a href="{{ route('jobs.show', $job->slug) }}">
                                            {{ $job->title }}
                                        </a>
                                    </h3>

                                    <!-- Company & Location -->
                                    <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-600">
                                        <span class="font-semibold text-slate-800 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $job->company_name }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $job->location }}
                                        </span>
                                        @if($job->salary_text)
                                            <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $job->salary_text }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Short Description Excerpt -->
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed pt-1">
                                        {{ $job->short_description }}
                                    </p>
                                </div>

                                <!-- Action Buttons & Deadline Column -->
                                <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                    <!-- Bookmark Save Button -->
                                    <form action="{{ route('jobs.save', $job->id) }}" method="POST">
                                        @csrf
                                        @php
                                            $isSaved = in_array($job->id, $userSavedJobIds);
                                        @endphp
                                        <button type="submit"
                                                title="{{ $isSaved ? 'Remove from Saved' : 'Save Job' }}"
                                                class="p-2 rounded-xl border transition flex items-center gap-1 text-xs font-semibold {{ $isSaved ? 'bg-red-50 text-red-600 border-red-200' : 'bg-slate-50 text-slate-500 border-slate-200 hover:text-red-500 hover:bg-red-50/50' }}">
                                            <svg class="w-4 h-4 {{ $isSaved ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span class="hidden sm:inline text-[11px]">{{ $isSaved ? 'Saved' : 'Save' }}</span>
                                        </button>
                                    </form>

                                    <!-- Deadline Countdown Badge -->
                                    <div class="text-right">
                                        @if($job->days_remaining <= 3)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 animate-pulse">
                                                <span>⚡</span> {{ $job->deadline_human }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $job->deadline_human }}
                                            </span>
                                        @endif
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            Till {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                                        </div>
                                    </div>

                                    <!-- View Details Button -->
                                    <a href="{{ route('jobs.show', $job->slug) }}"
                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1">
                                        <span>View</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $jobs->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                    <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">No Ongoing Jobs Found</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                        No current circulars matched your search criteria. Try removing filters or searching with a broader keyword.
                    </p>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                        Reset All Filters
                    </a>
                </div>
            @endif

        </main>
    </div>
</div>
@endsection
