<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\ProjectController;
use App\Http\Controllers\Tenant\ServiceController;
use App\Http\Controllers\Tenant\TaskController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    'auth',
    InitializeTenancyByPath::class,
    // InitializeTenancyByDomain::class,
    // PreventAccessFromCentralDomains::class,
])
    ->prefix('/{tenant}')
    ->name('tenant.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('tenant.dashboard');
        })->name('dashboard');

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Service routes
        Route::resource('services', ServiceController::class);

        // Project routes
        Route::resource('projects', ProjectController::class);

        // Task routes
        Route::resource('tasks', TaskController::class);
    });
