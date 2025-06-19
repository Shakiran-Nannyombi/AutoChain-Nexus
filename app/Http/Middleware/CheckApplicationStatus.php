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

        // Define role-based dashboard routes
        $dashboardRoutes = [
            'admin' => 'admin',
            'supplier' => 'supplier',
            'manufacturer' => 'manufacturer',
            'retailer' => 'retailer',
            'vendor' => 'vendors',
            'analyst' => 'analyst',
        ];

        // If user is authenticated and their status is 'pending' and they are not already on the status page
        if ($user->status === 'pending' && $request->route()->getName() !== 'application-status' && $user->role !== 'admin') {
            // Redirect to the application status page
            return redirect()->route('application-status');
        }

        // If user is approved (or admin) and tries to access a dashboard other than their specific one
        if ($user->status === 'approved' || $user->role === 'admin') {
            $intendedRoute = $dashboardRoutes[$user->role] ?? null;
            $currentRouteName = $request->route()->getName();

            // If the user's role has a defined dashboard route
            if ($intendedRoute && $currentRouteName !== $intendedRoute) {
                // Allow access to application-status if they are navigating away from it after initial registration/approval
                // However, prevent access to generic dashboard or other roles' dashboards
                if ($currentRouteName === 'application-status') {
                    return $next($request);
                } elseif (!in_array($currentRouteName, array_values($dashboardRoutes)) && $currentRouteName !== 'dashboard') { // Allow non-dashboard routes
                    return $next($request);
                } else {
                    return redirect()->route($intendedRoute);
                }
            }
        }

        return $next($request);
    }
}
