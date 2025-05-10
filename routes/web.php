<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Tenant\ServiceController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // Route::get('/', fn () => view('welcome'));
        Route::get('/', [ServiceController::class, 'index']);
        require __DIR__ . '/auth.php';

        Route::prefix('admin')
            ->middleware(['auth', IsAdmin::class])
            ->group(function () {
                Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');

                // Profile routes
                Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
                Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
                Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');

                // Country routes
                Route::resource('countries', CountryController::class, ['as' => 'admin']);

                // Language routes
                Route::resource('languages', LanguageController::class, ['as' => 'admin']);
            });
    });

}
