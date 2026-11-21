@extends('layouts.app')

@section('title', $job->title . ' at ' . $job->company_name . ' - BD JobFinder')
@section('meta_description', Str::limit($job->short_description, 150))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('jobs.index') }}" class="hover:text-blue-600 transition">Job Explorer</a>
        <span>/</span>
        <a href="{{ route('jobs.index', ['category' => $job->category]) }}" class="hover:text-blue-600 transition capitalize">{{ $job->category }}</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate max-w-xs">{{ $job->title }}</span>
    </nav>

    <!-- Main Job Detail Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

        <!-- Left 2 Cols: Main Info, Description, Application Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Title & Company Header Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm relative">
                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <!-- Source Badge -->
                    @if($job->source_name === 'Teletalk')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Teletalk Govt
                        </span>
                    @elseif($job->source_name === 'Bdjobs')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            Bdjobs Portal
                        </span>
                    @elseif($job->source_name === 'LinkedIn')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                            LinkedIn BD
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                            {{ $job->source_name }}
                        </span>
                    @endif

                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                        {{ $job->category === 'govt' ? 'bg-emerald-100 text-emerald-800' : '' }}
                        {{ $job->category === 'remote' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $job->category === 'ngo' ? 'bg-cyan-100 text-cyan-800' : '' }}
                        {{ $job->category === 'private' ? 'bg-slate-100 text-slate-700' : '' }}">
                        {{ $job->category }}
                    </span>

                    <span class="px-2.5 py-1 rounded text-xs font-medium bg-slate-100 text-slate-700 capitalize">
                        {{ $job->experience_level }} level
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                    {{ $job->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-y-2 gap-x-5 text-sm text-slate-600">
                    <span class="font-bold text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ $job->company_name }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $job->location }}
                    </span>
                    <span class="text-slate-400">Published {{ $job->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Job Description & Highlights -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Role Summary & Responsibilities</span>
                    </h2>
                    <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed">
                        <p>{{ $job->short_description }}</p>
                    </div>
                </div>

                <!-- Aggregator Transparency Disclosure -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1.5">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Aggregator Information & Direct Application Notice</span>
                    </div>
                    <p>
                        This circular was automatically fetched and indexed from <strong>{{ $job->source_name }}</strong>.
                        BD JobFinder does not accept resumes or charges any fee. Clicking <em>"Apply on Original Site"</em> will securely redirect you directly to the verified original publisher.
                    </p>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Key Metadata & Action Card -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Primary Action Widget -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-20 space-y-6">
                <!-- Deadline Status Header -->
                <div class="p-4 rounded-xl {{ $job->days_remaining <= 3 ? 'bg-amber-50 border border-amber-200 text-amber-900' : 'bg-blue-50 border border-blue-200 text-blue-900' }}">
                    <div class="text-xs font-semibold uppercase tracking-wider mb-1">Application Deadline</div>
                    <div class="text-xl font-extrabold flex items-center gap-2">
                        @if($job->days_remaining <= 3)
                            <span>⚡</span>
                        @endif
                        <span>{{ $job->deadline_human }}</span>
                    </div>
                    <div class="text-xs mt-1 text-slate-600">
                        Final Date: <strong>{{ \Carbon\Carbon::parse($job->deadline)->format('F j, Y') }}</strong>
                    </div>
                </div>

                <!-- Meta Specs List -->
                <div class="space-y-3.5 text-xs">
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Estimated Salary</span>
                        <span class="font-bold text-slate-800 text-right">{{ $job->salary_text ?? 'Negotiable' }}</span>
                    </div>

                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Vacancies</span>
                        <span class="font-bold text-slate-800">{{ $job->vacancy ? $job->vacancy . ' Positions' : 'Not specified' }}</span>
                    </div>

                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Experience Level</span>
                        <span class="font-bold text-slate-800 capitalize">{{ $job->experience_level }}</span>
                    </div>

                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Location</span>
                        <span class="font-bold text-slate-800">{{ $job->location }}</span>
                    </div>

                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Source Portal</span>
                        <span class="font-bold text-blue-600">{{ $job->source_name }}</span>
                    </div>
                </div>

                <!-- Buttons: Apply & Save -->
                <div class="space-y-3 pt-2">
                    <!-- Prominent Apply on Original Site Button -->
                    <a href="{{ route('jobs.apply', $job->slug) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 transition flex items-center justify-center gap-2 text-center group">
                        <span>Apply on Original Site</span>
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>

                    @auth
                        @if($hasApplied)
                            <div class="text-[11px] text-center text-emerald-600 font-semibold flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>You clicked to apply for this job</span>
                            </div>
                        @endif
                    @endauth

                    <!-- Bookmark Button -->
                    <form action="{{ route('jobs.save', $job->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 px-4 rounded-xl border text-xs font-semibold transition flex items-center justify-center gap-2 {{ $isSaved ? 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            <svg class="w-4 h-4 {{ $isSaved ? 'fill-current text-red-600' : 'fill-none text-slate-500' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>{{ $isSaved ? 'Remove from Saved Jobs' : 'Bookmark / Save Job' }}</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <!-- Related Ongoing Jobs Section -->
    @if($relatedJobs->count() > 0)
        <div class="border-t border-slate-200 pt-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Related Ongoing Jobs</h3>
                    <p class="text-xs text-slate-500">Other active positions in similar categories or locations</p>
                </div>
                <a href="{{ route('jobs.index', ['category' => $job->category]) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                    View More &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($relatedJobs as $relJob)
                    <div class="bg-white rounded-xl p-4 border border-slate-200 hover:border-blue-300 hover:shadow-xs transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] mb-2">
                                <span class="px-2 py-0.5 rounded-full font-bold bg-slate-100 text-slate-600 uppercase text-[9px]">{{ $relJob->category }}</span>
                                <span class="text-slate-500 font-semibold">{{ $relJob->deadline_human }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900 hover:text-blue-600 transition line-clamp-2 mb-1">
                                <a href="{{ route('jobs.show', $relJob->slug) }}">{{ $relJob->title }}</a>
                            </h4>
                            <p class="text-xs text-slate-600 font-medium line-clamp-1 mb-2">{{ $relJob->company_name }}</p>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500">{{ $relJob->location }}</span>
                            <a href="{{ route('jobs.show', $relJob->slug) }}" class="text-blue-600 font-bold hover:underline">Details &rarr;</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
