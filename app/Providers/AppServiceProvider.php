<?php

namespace App\Providers;

use App\Models\Bestelling;
use App\Observers\BestellingObserver;
use Illuminate\Support\ServiceProvider;

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
        Bestelling::observe(BestellingObserver::class);
    }
}
