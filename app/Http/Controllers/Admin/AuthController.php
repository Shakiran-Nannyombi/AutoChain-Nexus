<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an admin login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records for an admin account.',
        ]);
    }

    /**
     * Log the admin out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Clear all authentication guards
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();
        
        // Clear all session data
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear any remember me cookies
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('remember_admin'));
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('remember_web'));
        
        // Add cache-busting headers to prevent browser back button access
        return redirect('/admin/login')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT'
            ]);
    }
}
