<?php

namespace App\Providers;

use App\Filament\Auth\Notifications\ResetPassword as SyncResetPasswordNotification;
use Filament\Auth\Notifications\ResetPassword as FilamentResetPasswordNotification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Envoi immédiat (sans file d'attente), comme Breeze par défaut.
        $this->app->bind(
            FilamentResetPasswordNotification::class,
            SyncResetPasswordNotification::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
