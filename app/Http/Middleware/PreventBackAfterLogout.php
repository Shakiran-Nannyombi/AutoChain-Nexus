<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventBackAfterLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated in any guard
        $isAuthenticated = Auth::guard('web')->check() || Auth::guard('admin')->check();
        
        // If not authenticated and trying to access protected routes, redirect to login
        if (!$isAuthenticated) {
            // Determine which login page to redirect to based on the route
            if (str_contains($request->path(), 'admin')) {
                return redirect('/admin/login')
                    ->withHeaders([
                        'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
                        'Pragma' => 'no-cache',
                        'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT'
                    ]);
            } else {
                return redirect('/login')
                    ->withHeaders([
                        'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
                        'Pragma' => 'no-cache',
                        'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT'
                    ]);
            }
        }

        $response = $next($request);

        // Add cache-busting headers to all responses for protected pages
        if ($response instanceof \Illuminate\Http\Response) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
        }

        return $response;
    }
} 