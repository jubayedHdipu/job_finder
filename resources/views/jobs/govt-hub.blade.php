@extends('layouts.app')

@section('title', 'Govt. & Bank Job Hub - Bangladesh Official Circulars')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Govt Hub Banner -->
    <div class="mb-8 bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 rounded-2xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden border border-emerald-800/60">
        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Official Bangladesh Government, Autonomous & State Bank Recruitment</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white mb-3">
                Government & Bank Job Hub
            </h1>
            <p class="text-emerald-100/90 text-sm sm:text-base leading-relaxed mb-6">
                Aggregated directly from Teletalk Online Application System, Bangladesh Bank, BCSC, and public service commission gazettes.
            </p>

            <div class="flex flex-wrap items-center gap-4 text-xs">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10">
                    <span class="text-emerald-300 font-bold text-lg mr-1.5">{{ $totalGovtJobs }}</span>
                    <span class="text-slate-200">Active Circulars</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10">
                    <span class="text-emerald-300 font-bold text-lg mr-1.5">{{ number_format($totalGovtVacancies) }}+</span>
                    <span class="text-slate-200">Open Vacancies</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10">
                    <span class="text-emerald-300 font-bold mr-1">Direct Teletalk Redirect</span>
                    <span class="text-slate-200">No Intermediary</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar for Govt Hub -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
        <form method="GET" action="{{ route('jobs.govt') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search ministry, bank, or post title..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="sm:col-span-3">
                <select name="sort" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Published</option>
                    <option value="deadline_asc" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>Deadline Ending Soonest</option>
                    <option value="vacancy_desc" {{ request('sort') == 'vacancy_desc' ? 'selected' : '' }}>Most Vacancies</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-xs transition">
                    Filter Circulars
                </button>
                @if(request()->hasAny(['q', 'sort', 'ending_soon']))
                    <a href="{{ route('jobs.govt') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition" title="Clear Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Govt Jobs High-Information Table / Cards -->
    @if($jobs->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider">
                        <tr>
                            <th scope="col" class="py-3.5 px-4 sm:px-6">Designation & Ministry / Organization</th>
                            <th scope="col" class="py-3.5 px-4">Pay Scale & Grade</th>
                            <th scope="col" class="py-3.5 px-4 text-center">Vacancies</th>
                            <th scope="col" class="py-3.5 px-4">Application Deadline</th>
                            <th scope="col" class="py-3.5 px-4">Source</th>
                            <th scope="col" class="py-3.5 px-4 text-right">Direct Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($jobs as $job)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900 text-sm hover:text-emerald-700 transition">
                                        <a href="{{ route('jobs.show', $job->slug) }}">
                                            {{ $job->title }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $job->company_name }}
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-slate-700">
                                    <span class="font-semibold">{{ $job->salary_text ?? 'Official Pay Scale' }}</span>
                                    <div class="text-[11px] text-slate-400 capitalize">{{ $job->experience_level }} level</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($job->vacancy)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800">
                                            {{ $job->vacancy }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">Multiple</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold {{ $job->days_remaining <= 3 ? 'text-amber-600 flex items-center gap-1' : 'text-slate-800' }}">
                                        @if($job->days_remaining <= 3)
                                            <span>⚡</span>
                                        @endif
                                        <span>{{ $job->deadline_human }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $job->source_name }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold transition">
                                            Details
                                        </a>
                                        <a href="{{ route('jobs.apply', $job->slug) }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-xs transition flex items-center gap-1">
                                            <span>Apply</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $jobs->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-1">No Government Circulars Found</h3>
            <p class="text-sm text-slate-500 mb-4">Try clearing your search query to see all active government positions.</p>
            <a href="{{ route('jobs.govt') }}" class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-xl text-xs">View All Govt Jobs</a>
        </div>
    @endif

</div>
@endsection
