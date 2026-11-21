@extends('layouts.app')

@section('title', 'Admin Scraper Control Panel - BD JobFinder')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header Section -->
    <div class="bg-slate-900 rounded-2xl p-6 sm:p-8 text-white border border-slate-800 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-3">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>Administrator Dashboard</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Scraper Engine & Job Operations</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Real-time link validation, auto-removal of expired jobs, and duplicate-safe ingestion.
            </p>
        </div>

        <!-- Action Control Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Run Scraper Now -->
            <form action="{{ route('admin.scrape') }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Run Scraper Now</span>
                </button>
            </form>

            <!-- Purge Expired Jobs -->
            <form action="{{ route('admin.purge.expired') }}" method="POST" onsubmit="return confirm('Purge and permanently delete all expired jobs past their deadline?')">
                @csrf
                <button type="submit"
                        class="px-3.5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5"
                        title="Remove all expired circulars from MySQL">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Auto-Purge Expired</span>
                </button>
            </form>

            <!-- Validate All Links -->
            <form action="{{ route('admin.validate.links') }}" method="POST" onsubmit="return confirm('Audit all stored URLs in MySQL and remove any broken or expired links?')">
                @csrf
                <button type="submit"
                        class="px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>Audit All URLs</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Ongoing Active Jobs</div>
            <div class="text-3xl font-black text-slate-900">{{ $ongoingJobsCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Live circulars on portal</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total Stored Jobs</div>
            <div class="text-3xl font-black text-slate-900">{{ $totalJobsCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">100% verified active in MySQL</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">Registered Users</div>
            <div class="text-3xl font-black text-purple-700">{{ $registeredUsersCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Active candidate accounts</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Scraper Logs Recorded</div>
            <div class="text-3xl font-black text-slate-700">{{ $totalScrapesRun }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Audit trail entries</div>
        </div>
    </div>

    <!-- Active Safeguards Information Box -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-emerald-50/70 border border-emerald-200 rounded-2xl p-5 text-xs text-emerald-950">
            <div class="flex items-center gap-2 font-bold text-sm text-emerald-900 mb-1.5">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Live URL Health Verification Active</span>
            </div>
            <p class="leading-relaxed text-emerald-800">
                Before any job is scraped or inserted into the database, an HTTP request tests if the source link resolves. Any 404, 500, or broken URL is strictly rejected. Only 100% working URLs enter the system.
            </p>
        </div>

        <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-5 text-xs text-amber-950">
            <div class="flex items-center gap-2 font-bold text-sm text-amber-900 mb-1.5">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Automatic Removal of Expired Jobs</span>
            </div>
            <p class="leading-relaxed text-amber-800">
                Whenever a job's application deadline passes, the system automatically removes it from the MySQL database during regular visits and scraper runs, guaranteeing visitors only see active, valid circulars.
            </p>
        </div>
    </div>

    <!-- Section 1: Scraper Execution Logs Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Recent Scraper Execution & Ingestion Logs</h2>
                <p class="text-xs text-slate-500">Tracking added jobs, duplicates, broken links rejected, and expired items filtered</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                {{ $totalScrapesRun }} Total Cycles Logged
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4 sm:px-6">Source</th>
                        <th class="py-3 px-4 text-center">New Added</th>
                        <th class="py-3 px-4 text-center">Duplicates</th>
                        <th class="py-3 px-4 text-center">Broken URLs Rejected</th>
                        <th class="py-3 px-4 text-center">Expired Filtered</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 sm:px-6">
                                <div class="font-bold text-slate-900">{{ $log->source_name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">#{{ $log->id }}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-extrabold {{ $log->new_jobs_added > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    +{{ $log->new_jobs_added }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $log->duplicates_skipped > 0 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $log->duplicates_skipped }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ ($log->invalid_links_skipped ?? 0) > 0 ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $log->invalid_links_skipped ?? 0 }} rejected
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ ($log->expired_skipped ?? 0) > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $log->expired_skipped ?? 0 }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $log->status === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500 whitespace-nowrap">
                                <div>{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->created_at->format('h:i:s A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                No scraper logs found. Click "Run Scraper Now" above to initiate a cycle.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Section 2: Quick Job Management Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">Job Catalog Management</h2>
                <p class="text-xs text-slate-500">All entries verified active with live working links</p>
            </div>

            <!-- Job Search & Filter -->
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text"
                       name="job_search"
                       value="{{ request('job_search') }}"
                       placeholder="Filter by title / company..."
                       class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-56">

                <select name="status_filter" class="py-1.5 px-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>

                <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white rounded-xl text-xs font-semibold">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4 sm:px-6">Job Title & Employer</th>
                        <th class="py-3 px-4">Category & Source</th>
                        <th class="py-3 px-4">Target Application URL</th>
                        <th class="py-3 px-4">Deadline</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 sm:px-6">
                                <a href="{{ route('jobs.show', $job->slug) }}" class="font-bold text-slate-900 hover:text-blue-600 transition">
                                    {{ $job->title }}
                                </a>
                                <div class="text-[11px] text-slate-500">{{ $job->company_name }} • {{ $job->location }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-700 capitalize">{{ $job->category }}</div>
                                <div class="text-[10px] text-blue-600 font-bold">{{ $job->source_name }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ $job->source_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline max-w-[200px] truncate block font-mono text-[11px]" title="{{ $job->source_url }}">
                                    {{ $job->source_url }}
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold {{ $job->days_remaining <= 3 ? 'text-amber-600' : 'text-slate-800' }}">
                                    {{ $job->deadline_human }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($job->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.jobs.toggle', $job->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-lg border text-[11px] font-semibold transition {{ $job->is_active ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                                            {{ $job->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.jobs.delete', $job->id) }}" method="POST" onsubmit="return confirm('Delete this job entry permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-700 transition" title="Delete Job">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                No jobs match the current filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $jobs->links() }}
        </div>
    </div>

</div>
@endsection
