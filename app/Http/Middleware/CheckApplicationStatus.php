<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApplicationStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If user is authenticated and their status is 'pending' and they are not already on the status page
        if ($user->status === 'pending' && $request->route()->getName() !== 'application-status' && $user->role !== 'admin') {
            // Redirect to the application status page
            return redirect()->route('application-status');
        }

        return $next($request);
    }
}
