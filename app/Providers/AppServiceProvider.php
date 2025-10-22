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
        // Register policies
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Penelitian::class,
            \App\Policies\PenelitianPolicy::class
        );

        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Pengabdian::class,
            \App\Policies\PengabdianPolicy::class
        );
    }

    
}
