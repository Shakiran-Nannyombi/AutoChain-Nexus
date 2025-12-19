<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProcessFlowRepositoryInterface;
use App\Repositories\Eloquent\ProcessFlowRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProcessFlowRepositoryInterface::class, ProcessFlowRepository::class);
        $this->app->bind(
            \App\Repositories\Contracts\VendorOrderRepositoryInterface::class,
            \App\Repositories\Eloquent\VendorOrderRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
