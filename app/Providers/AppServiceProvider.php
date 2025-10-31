<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Register Model Observers untuk auto cache invalidation
        \App\Models\HasilKuesioner::observe(\App\Observers\HasilKuesionerObserver::class);
        \App\Models\DataDiris::observe(\App\Observers\DataDirisObserver::class);
    }
}
