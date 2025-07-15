<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @method \Illuminate\Database\Eloquent\Relations\MorphMany notifications()
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $user = Auth::guard('admin')->user() ?? Auth::user();
            if ($user instanceof Model && method_exists($user, 'notifications')) {
                $unreadNotifications = $user->notifications()->whereNull('read_at')->take(5)->get();
                $allNotifications = $user->notifications()->take(10)->get();
            } else {
                $unreadNotifications = collect();
                $allNotifications = collect();
            }
            $view->with('unreadNotifications', $unreadNotifications)
                ->with('allNotifications', $allNotifications);
        });
    }
}
