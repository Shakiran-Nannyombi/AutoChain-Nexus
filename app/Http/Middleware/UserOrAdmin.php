<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('UserOrAdmin middleware hit');
        if (Auth::guard('web')->check() || Auth::guard('admin')->check() || session('user_role') === 'admin') {
            return $next($request);
        }
        return redirect()->route('login');
    }
} 