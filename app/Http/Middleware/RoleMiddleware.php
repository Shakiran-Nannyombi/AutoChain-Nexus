<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user() ?? \App\Models\User::find(session('user_id'));

        if (!$user || $user->role !== $role) {
            // Optionally, redirect or abort
            return redirect('/login');
        }

        return $next($request);
    }
} 