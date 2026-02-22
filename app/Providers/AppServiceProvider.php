<?php

namespace App\Providers;

use App\Models\Clinic;
use Illuminate\Support\Facades\View;
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
        View::composer('dashboard.layout', function ($view) {
            $view->with('clinic', Clinic::where('is_active', true)->first() ?? Clinic::first());
        });
    }
}
