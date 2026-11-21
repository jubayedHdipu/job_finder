<?php

namespace App\Http\Controllers;

use App\Models\AppliedHistory;
use App\Models\ScrapedJob;
use App\Services\JobScraperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobController extends Controller
{
    /**
     * Display front page job explorer with multi-faceted filtering.
     */
    public function index(Request $request, JobScraperService $scraper): View
    {
        // Automatically purge any expired jobs from the system
        $scraper->purgeExpiredJobs();

        $query = ScrapedJob::ongoing();

        // Search Keyword (Title, Company, Description)
        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Location Filter
        if ($location = $request->input('location')) {
            if ($location !== 'All') {
                $query->where('location', 'like', "%{$location}%");
            }
        }

        // Source Portal Filter
        if ($source = $request->input('source')) {
            if ($source !== 'All') {
                $query->where('source_name', $source);
            }
        }

        // Category Filter
        if ($category = $request->input('category')) {
            if ($category !== 'All') {
                $query->where('category', $category);
            }
        }

        // Experience Level Filter
        if ($experience = $request->input('experience')) {
            if ($experience !== 'All') {
                $query->where('experience_level', $experience);
            }
        }

        // Quick filter: Ending in 3 Days
        if ($request->boolean('ending_soon')) {
            $query->endingSoon(3);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'deadline_asc' => $query->orderBy('deadline', 'asc'),
            'deadline_desc' => $query->orderBy('deadline', 'desc'),
            'title_asc' => $query->orderBy('title', 'asc'),
            default => $query->orderBy('id', 'desc'),
        };

        $jobs = $query->paginate(12)->withQueryString();

        // Facets for filter dropdowns
        $availableLocations = ['All', 'Dhaka', 'Chattogram', 'Sylhet', 'Gazipur', 'Rajshahi', 'Remote'];
        $availableSources = ['All', 'Bdjobs', 'Teletalk', 'Skill.jobs', 'LinkedIn'];
        $availableCategories = [
            'All' => 'All Categories',
            'private' => 'Private Enterprise',
            'govt' => 'Govt. & Bank',
            'ngo' => 'NGO & Development',
            'remote' => 'Remote / Global',
        ];
        $availableExperiences = [
            'All' => 'All Levels',
            'fresher' => 'Fresher / Entry',
            'mid' => 'Mid-Level (1-4 Yrs)',
            'senior' => 'Senior / Lead (5+ Yrs)',
        ];

        $totalOngoingCount = ScrapedJob::ongoing()->count();
        $endingSoonCount = ScrapedJob::ongoing()->endingSoon(3)->count();
        $govtCount = ScrapedJob::ongoing()->where('category', 'govt')->count();

        // Get saved job IDs for authenticated user for instant heart status
        $userSavedJobIds = Auth::check()
            ? Auth::user()->savedJobs()->pluck('scraped_job_id')->toArray()
            : [];

        return view('jobs.index', compact(
            'jobs',
            'availableLocations',
            'availableSources',
            'availableCategories',
            'availableExperiences',
            'totalOngoingCount',
            'endingSoonCount',
            'govtCount',
            'userSavedJobIds'
        ));
    }

    /**
     * Display a specific job's details and related ongoing circulars.
     */
    public function show(string $slug): View
    {
        $job = ScrapedJob::where('slug', $slug)->firstOrFail();

        // If job deadline has already expired, remove it automatically
        if ($job->is_expired) {
            $job->delete();
            abort(404, 'This job listing has expired and has been removed from our system.');
        }

        // Fetch related ongoing jobs (same category or similar location)
        $relatedJobs = ScrapedJob::ongoing()
            ->where('id', '!=', $job->id)
            ->where(function ($q) use ($job) {
                $q->where('category', $job->category)
                    ->orWhere('location', $job->location);
            })
            ->latest('id')
            ->take(4)
            ->get();

        $isSaved = Auth::check() ? Auth::user()->hasSavedJob($job->id) : false;
        $hasApplied = Auth::check() ? Auth::user()->hasApplied($job->id) : false;

        return view('jobs.show', compact('job', 'relatedJobs', 'isSaved', 'hasApplied'));
    }

    /**
     * Track external job application click and redirect to original source portal.
     */
    public function applyRedirect(Request $request, string $slug): RedirectResponse
    {
        $job = ScrapedJob::where('slug', $slug)->firstOrFail();

        // If job expired, remove it automatically from database
        if ($job->is_expired) {
            $job->delete();

            return redirect()->route('jobs.index')
                ->with('error', 'This job circular has expired and has been automatically removed from our system.');
        }

        if (Auth::check()) {
            AppliedHistory::firstOrCreate([
                'user_id' => Auth::id(),
                'scraped_job_id' => $job->id,
            ], [
                'clicked_at' => now(),
            ]);
        }

        return redirect()->away($job->source_url);
    }

    /**
     * Dedicated Govt & Bank Job Hub page.
     */
    public function govtHub(Request $request, JobScraperService $scraper): View
    {
        // Automatically purge any expired jobs
        $scraper->purgeExpiredJobs();

        $query = ScrapedJob::ongoing()->where('category', 'govt');

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('ending_soon')) {
            $query->endingSoon(5);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'deadline_asc' => $query->orderBy('deadline', 'asc'),
            'vacancy_desc' => $query->orderBy('vacancy', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        $jobs = $query->paginate(15)->withQueryString();

        $totalGovtJobs = ScrapedJob::ongoing()->where('category', 'govt')->count();
        $totalGovtVacancies = (int) ScrapedJob::ongoing()->where('category', 'govt')->sum('vacancy');

        $userSavedJobIds = Auth::check()
            ? Auth::user()->savedJobs()->pluck('scraped_job_id')->toArray()
            : [];

        return view('jobs.govt-hub', compact(
            'jobs',
            'totalGovtJobs',
            'totalGovtVacancies',
            'userSavedJobIds'
        ));
    }
}
