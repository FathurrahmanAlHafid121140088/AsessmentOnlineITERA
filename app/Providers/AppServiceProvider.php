<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

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

        // ========================================
        // PERFORMANCE: Prevent Lazy Loading (N+1 Detection)
        // ========================================
        // Throw exception ketika ada lazy loading di development
        // Ini memaksa developer untuk selalu menggunakan eager loading
        Model::preventLazyLoading(!app()->isProduction());

        // Prevent silently discarding attributes (keamanan data)
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        // Prevent accessing missing attributes (bug detection)
        Model::preventAccessingMissingAttributes(!app()->isProduction());

        // Force HTTPS in production (Railway fix)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
