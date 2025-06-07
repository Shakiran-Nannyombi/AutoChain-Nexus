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
        $user = Auth::user();

        // If user is authenticated and their status is 'pending' and they are not already on the status page
        if ($user && $user->status === 'pending' && $request->route()->getName() !== 'application-status') {
            // Redirect to the application status page
            return redirect()->route('application-status');
        }

        return $next($request);
    }
}
