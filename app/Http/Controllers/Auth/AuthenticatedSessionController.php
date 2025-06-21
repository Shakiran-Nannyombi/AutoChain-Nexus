<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('AuthenticatedSessionController@store method entered.');
        $request->authenticate();

        // Check if an admin user has been authenticated
        if (Auth::guard('admin')->check()) {
            Log::info('Admin guard checked and returned true.');
            Log::info('Authenticated Admin User ID: ' . Auth::guard('admin')->id());
            Log::info('Redirecting admin to: ' . route('admin.dashboard'));
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // If not an admin, proceed with regular user checks
        $user = Auth::user();
        Log::info('Regular user login attempt for: ' . ($user ? $user->email : 'N/A'));

        // Check if the authenticated user is an instance of the User model
        if ($user instanceof \App\Models\User) {
            // If user is pending, redirect to application status
            if ($user->isPending()) {
                Log::info('Redirecting pending user to: ' . route('application-status'));
                $request->session()->regenerate();
                return redirect()->route('application-status');
            }

            // For approved users, redirect to their role-specific dashboard
            switch ($user->role) {
                case 'analyst':
                    $request->session()->regenerate();
                    return redirect()->route('analyst');
                case 'manufacturer':
                    $request->session()->regenerate();
                    return redirect()->route('manufacturer');
                case 'supplier':
                    $request->session()->regenerate();
                    return redirect()->route('supplier');
                default:
                    $request->session()->regenerate();
                    return redirect()->route('dashboard');
            }
        }
        Log::info('No specific redirection for user. Proceeding with default Laravel user redirection.');
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Check which guard the user is logged in with and log them out appropriately
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
