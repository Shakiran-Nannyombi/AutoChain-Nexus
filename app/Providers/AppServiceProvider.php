<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMailer;
use App\Mail\SymfonyEventDispatcherAdapter;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Illuminate\Contracts\Events\Dispatcher as LaravelDispatcherContract;
use Illuminate\Events\Dispatcher as LaravelDispatcherImplementation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Explicitly bind Symfony's EventDispatcherInterface to our adapter
        $this->app->bind(EventDispatcherInterface::class, function ($app) {
            return new SymfonyEventDispatcherAdapter($app->make(LaravelDispatcherImplementation::class));
        });

        // Bind Psr\Log\LoggerInterface to Laravel's default log channel
        $this->app->bind(LoggerInterface::class, function ($app) {
            return $app->make('log');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share notifications data with header partial
        View::composer('layouts.partials.header', function ($view) {
            $user = Auth::user();
            $unreadNotifications = $user ? $user->unreadNotifications->count() : 0;
            $notifications = $user ? $user->notifications->take(5) : collect();
            
            $view->with([
                'unreadNotifications' => $unreadNotifications,
                'notifications' => $notifications
            ]);
        });

        // Register custom mailer
        Mail::extend('java', function (array $config) {
            // Resolve the Dispatcher and Logger from the container based on our explicit binds
            $dispatcher = $this->app->make(EventDispatcherInterface::class);
            $logger = $this->app->make(LoggerInterface::class);

            return new CustomMailer(
                $config['base_url'] ?? 'http://localhost:8081',
                $config['username'] ?? 'admin',
                $config['password'] ?? 'password',
                $dispatcher,
                $logger
            );
        });
    }
}
