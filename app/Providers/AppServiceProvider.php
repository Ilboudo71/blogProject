<?php

namespace App\Providers;

use App\Filament\Auth\Notifications\ResetPassword as SyncResetPasswordNotification;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegistrationResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Filament\Auth\Notifications\ResetPassword as FilamentResetPasswordNotification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegistrationResponseContract::class, RegistrationResponse::class);

        // Envoi immédiat (sans file d'attente), comme Breeze par défaut.
        $this->app->bind(
            FilamentResetPasswordNotification::class,
            SyncResetPasswordNotification::class
        );
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
