<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $request->authenticate();

        $request->session()->regenerate();

        // Check if an admin user has been authenticated
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin');
        }

        // If not an admin, proceed with regular user checks
        $user = Auth::user();

        // Check if the authenticated user is an instance of the User model
        if ($user instanceof \App\Models\User) {
            // If user is pending, redirect to application status
            if ($user->isPending()) {
                return redirect()->route('application-status');
            }

            // For approved users, redirect to their role-specific dashboard
            switch ($user->role) {
                case 'analyst':
                    return redirect()->route('analyst');
                case 'manufacturer':
                    return redirect()->route('manufacturer');
                case 'supplier':
                    return redirect()->route('supplier');
                default:
                    return redirect()->route('dashboard');
            }
        }

        // Fallback for any other unhandled authenticated user type
        return redirect()->route('dashboard'); // Or a more appropriate default route
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
