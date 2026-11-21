<?php

namespace App\Http\Controllers;

use App\Models\ScrapedJob;
use App\Models\ScraperLog;
use App\Models\User;
use App\Services\JobScraperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display scraper control panel & management dashboard.
     */
    public function index(Request $request): View
    {
        // Platform Metrics
        $ongoingJobsCount = ScrapedJob::ongoing()->count();
        $totalJobsCount = ScrapedJob::count();
        $registeredUsersCount = User::count();
        $totalScrapesRun = ScraperLog::count();

        // Scraper logs with pagination
        $logs = ScraperLog::latest('id')->paginate(10, ['*'], 'logs_page');

        // Jobs management query with search filter
        $jobsQuery = ScrapedJob::latest('id');
        if ($search = trim((string) $request->input('job_search'))) {
            $jobsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('source_name', 'like', "%{$search}%");
            });
        }
        if ($statusFilter = $request->input('status_filter')) {
            if ($statusFilter === 'active') {
                $jobsQuery->where('is_active', true);
            } elseif ($statusFilter === 'inactive') {
                $jobsQuery->where('is_active', false);
            }
        }

        $jobs = $jobsQuery->paginate(15, ['*'], 'jobs_page')->withQueryString();

        return view('admin.dashboard', compact(
            'ongoingJobsCount',
            'totalJobsCount',
            'registeredUsersCount',
            'totalScrapesRun',
            'logs',
            'jobs'
        ));
    }

    /**
     * Trigger background scraper engine manually from the UI.
     */
    public function runScraper(JobScraperService $scraper): RedirectResponse
    {
        $results = $scraper->scrapeAll();

        $totalAdded = 0;
        $totalSkipped = 0;
        $totalInvalid = 0;
        $totalExpired = 0;

        foreach ($results as $res) {
            $totalAdded += $res['added'];
            $totalSkipped += $res['skipped'];
            $totalInvalid += $res['invalid'] ?? 0;
            $totalExpired += $res['expired'] ?? 0;
        }

        return redirect()->route('admin.dashboard')->with(
            'success',
            "Scraper cycle executed: {$totalAdded} new jobs added, {$totalSkipped} duplicates skipped, {$totalInvalid} broken links rejected, {$totalExpired} expired skipped."
        );
    }

    /**
     * Trigger manual purge of all expired jobs.
     */
    public function purgeExpired(JobScraperService $scraper): RedirectResponse
    {
        $count = $scraper->purgeExpiredJobs();

        return redirect()->route('admin.dashboard')->with(
            'success',
            "Expired jobs cleanup executed: {$count} outdated jobs permanently deleted from MySQL."
        );
    }

    /**
     * Audit and sweep entire database: validate all URLs and remove any broken or expired entries.
     */
    public function validateAllLinks(JobScraperService $scraper): RedirectResponse
    {
        $results = $scraper->sweepAndValidateDatabase();

        return redirect()->route('admin.dashboard')->with(
            'success',
            "Database URL audit complete: Checked {$results['total_checked']} circulars. Removed {$results['broken_links_removed']} broken links and purged {$results['purged_expired']} expired jobs."
        );
    }

    /**
     * Toggle active/inactive status of a job.
     */
    public function toggleJobStatus(int $id): RedirectResponse
    {
        $job = ScrapedJob::findOrFail($id);
        $job->is_active = ! $job->is_active;
        $job->save();

        $statusStr = $job->is_active ? 'Activated' : 'Deactivated';

        return back()->with('success', "Job #{$job->id} ({$job->title}) has been {$statusStr}.");
    }

    /**
     * Delete an invalid job listing.
     */
    public function deleteJob(int $id): RedirectResponse
    {
        $job = ScrapedJob::findOrFail($id);
        $title = $job->title;
        $job->delete();

        return back()->with('success', "Job \"{$title}\" was successfully deleted.");
    }
}
