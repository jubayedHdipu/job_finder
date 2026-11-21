<?php

namespace App\Http\Controllers;

use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $notice = "Welcome back, {$user->name}!";

            // Process guest save intent if pending
            if ($pendingJobId = session()->pull('redirect_save_job_id')) {
                SavedJob::firstOrCreate([
                    'user_id' => $user->id,
                    'scraped_job_id' => $pendingJobId,
                ]);
                $notice = "Welcome back, {$user->name}! We've also saved your requested job to your bookmarks.";
            }

            if ($user->isAdmin() && ! session()->has('url.intended')) {
                return redirect()->route('admin.dashboard')->with('success', $notice);
            }

            return redirect()->intended(route('dashboard'))->with('success', $notice);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $notice = "Account created successfully! Welcome to BD JobFinder, {$user->name}.";

        // Automatically save pending job if clicked while guest
        if ($pendingJobId = session()->pull('redirect_save_job_id')) {
            SavedJob::firstOrCreate([
                'user_id' => $user->id,
                'scraped_job_id' => $pendingJobId,
            ]);
            $notice .= " We've automatically added your selected job to your bookmarks!";
        }

        return redirect()->route('dashboard')->with('success', $notice);
    }

    /**
     * Log user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
