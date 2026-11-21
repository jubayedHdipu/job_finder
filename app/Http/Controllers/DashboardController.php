<?php

namespace App\Http\Controllers;

use App\Models\AppliedHistory;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show authenticated user dashboard with Saved and Applied jobs.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $activeTab = $request->query('tab', 'saved');

        // Paginated Saved Jobs
        $savedJobs = SavedJob::with('job')
            ->where('user_id', $user->id)
            ->latest('id')
            ->paginate(10, ['*'], 'saved_page');

        // Paginated Applied History
        $appliedJobs = AppliedHistory::with('job')
            ->where('user_id', $user->id)
            ->latest('clicked_at')
            ->paginate(10, ['*'], 'applied_page');

        // Statistics
        $totalSaved = SavedJob::where('user_id', $user->id)->count();
        $totalApplied = AppliedHistory::where('user_id', $user->id)->count();

        // Active vs Expired count among saved jobs
        $activeSavedCount = SavedJob::where('user_id', $user->id)
            ->whereHas('job', function ($q) {
                $q->ongoing();
            })->count();

        $expiredSavedCount = $totalSaved - $activeSavedCount;

        return view('dashboard.index', compact(
            'user',
            'activeTab',
            'savedJobs',
            'appliedJobs',
            'totalSaved',
            'totalApplied',
            'activeSavedCount',
            'expiredSavedCount'
        ));
    }
}
