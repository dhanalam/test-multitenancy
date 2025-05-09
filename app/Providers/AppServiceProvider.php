<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
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
        if (tenant()) {
            RedirectIfAuthenticated::redirectUsing(fn () => route('dashboard', ['tenant' => tenant()]));
        }
    }
}
