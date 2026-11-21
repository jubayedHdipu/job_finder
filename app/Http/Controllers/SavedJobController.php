<?php

namespace App\Http\Controllers;

use App\Models\SavedJob;
use App\Models\ScrapedJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    /**
     * Toggle bookmark/save status for a job.
     */
    public function toggle(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $job = ScrapedJob::findOrFail($id);

        if (! Auth::check()) {
            // Guest-first behavior: store intent in session and redirect to login
            session(['redirect_save_job_id' => $job->id]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'auth_required',
                    'redirect_url' => route('login'),
                    'message' => 'Please log in to bookmark this job.',
                ], 401);
            }

            return redirect()->route('login')
                ->with('info', "Please log in or register to bookmark '{$job->title}'. It will be saved automatically once you sign in.");
        }

        $userId = Auth::id();
        $saved = SavedJob::where('user_id', $userId)
            ->where('scraped_job_id', $job->id)
            ->first();

        if ($saved) {
            $saved->delete();
            $isSaved = false;
            $message = 'Job removed from your saved bookmarks.';
        } else {
            SavedJob::create([
                'user_id' => $userId,
                'scraped_job_id' => $job->id,
            ]);
            $isSaved = true;
            $message = 'Job saved to your bookmarks!';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'is_saved' => $isSaved,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove a saved job from user dashboard.
     */
    public function destroy(int $id): RedirectResponse
    {
        $saved = SavedJob::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $saved->delete();

        return back()->with('success', 'Job removed from your saved list.');
    }
}
