@extends('layouts.app')

@section('title', 'My Dashboard - BD JobFinder')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Dashboard Header -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 to-blue-500 text-white flex items-center justify-center font-extrabold text-xl shadow-md shadow-blue-500/20">
                {{ substr($user->name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h1>
                <p class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                    <span>{{ $user->email }}</span>
                    <span>•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                        {{ $user->role }}
                    </span>
                    <span>•</span>
                    <span>Member since {{ $user->created_at->format('M Y') }}</span>
                </p>
            </div>
        </div>

        @if($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <span>Admin Scraper Panel</span>
            </a>
        @endif
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Saved Jobs</div>
            <div class="text-2xl font-black text-slate-900">{{ $totalSaved }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Bookmarked circulars</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Ongoing Saved</div>
            <div class="text-2xl font-black text-emerald-600">{{ $activeSavedCount }}</div>
            <div class="text-[11px] text-emerald-700 mt-1">Deadline still open</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Expired Saved</div>
            <div class="text-2xl font-black text-slate-500">{{ $expiredSavedCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Past deadline</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Applied / Visited</div>
            <div class="text-2xl font-black text-blue-600">{{ $totalApplied }}</div>
            <div class="text-[11px] text-blue-700 mt-1">Outbound application clicks</div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex items-center gap-2 border-b border-slate-200 mb-6">
        <a href="{{ route('dashboard', ['tab' => 'saved']) }}"
           class="pb-3 px-4 text-sm font-bold border-b-2 transition flex items-center gap-2 {{ $activeTab === 'saved' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
            <span>Saved Jobs ({{ $totalSaved }})</span>
        </a>
        <a href="{{ route('dashboard', ['tab' => 'applied']) }}"
           class="pb-3 px-4 text-sm font-bold border-b-2 transition flex items-center gap-2 {{ $activeTab === 'applied' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span>Applied History ({{ $totalApplied }})</span>
        </a>
    </div>

    <!-- TAB 1: SAVED JOBS -->
    @if($activeTab === 'saved')
        @if($savedJobs->count() > 0)
            <div class="space-y-4">
                @foreach($savedJobs as $saved)
                    @php $job = $saved->job; @endphp
                    @if($job)
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1.5 flex-1">
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    @if($job->is_expired)
                                        <span class="px-2.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            Expired
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Ongoing • {{ $job->deadline_human }}
                                        </span>
                                    @endif

                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                        {{ $job->category }}
                                    </span>
                                    <span class="text-slate-400 text-[11px]">• Saved {{ $saved->created_at->diffForHumans() }}</span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 hover:text-blue-600 transition">
                                    <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                                </h3>

                                <div class="flex flex-wrap items-center gap-x-4 text-xs text-slate-500">
                                    <span class="font-semibold text-slate-700">{{ $job->company_name }}</span>
                                    <span>{{ $job->location }}</span>
                                    @if($job->salary_text)
                                        <span class="text-emerald-700 font-semibold">{{ $job->salary_text }}</span>
                                    @endif
                                    <span>Source: {{ $job->source_name }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <a href="{{ route('jobs.show', $job->slug) }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                                    Details
                                </a>
                                <a href="{{ route('jobs.apply', $job->slug) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-1">
                                    <span>Apply</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                                <form action="{{ route('saved-jobs.destroy', $saved->id) }}" method="POST" onsubmit="return confirm('Remove this job from bookmarks?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl border border-slate-200 text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Remove Bookmark">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-6">
                {{ $savedJobs->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">No Saved Jobs Yet</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">Click the bookmark icon on any job circular while browsing to save it for later review.</p>
                <a href="{{ route('jobs.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold">Explore Ongoing Jobs</a>
            </div>
        @endif

    <!-- TAB 2: APPLIED HISTORY -->
    @elseif($activeTab === 'applied')
        @if($appliedJobs->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider">
                            <tr>
                                <th scope="col" class="py-3.5 px-4 sm:px-6">Job Position & Employer</th>
                                <th scope="col" class="py-3.5 px-4">Category</th>
                                <th scope="col" class="py-3.5 px-4">Applied / Clicked Date</th>
                                <th scope="col" class="py-3.5 px-4">Application Deadline</th>
                                <th scope="col" class="py-3.5 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($appliedJobs as $history)
                                @php $job = $history->job; @endphp
                                @if($job)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="py-4 px-4 sm:px-6">
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="font-bold text-slate-900 text-sm hover:text-blue-600 transition">
                                                {{ $job->title }}
                                            </a>
                                            <div class="text-xs text-slate-500 font-medium">{{ $job->company_name }} • {{ $job->location }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                                {{ $job->category }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-slate-700 font-medium">
                                            <div>{{ $history->clicked_at ? $history->clicked_at->format('M d, Y') : $history->created_at->format('M d, Y') }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $history->clicked_at ? $history->clicked_at->format('h:i A') : '' }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($job->is_expired)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500">Expired</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">{{ $job->deadline_human }}</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <a href="{{ route('jobs.apply', $job->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs inline-flex items-center gap-1">
                                                <span>Re-visit Portal</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100">
                    {{ $appliedJobs->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">No Application History</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">When you click "Apply on Original Site", we record your application timestamp so you can track all your submissions in one place.</p>
                <a href="{{ route('jobs.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold">Browse Available Jobs</a>
            </div>
        @endif
    @endif

</div>
@endsection
