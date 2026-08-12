<?php

namespace App\Filament\Auth;

use App\Models\User;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            /** @var User $user */
            $user = Filament::auth()->user();

            redirect()->intended($user->panelHomeUrl());

            return;
        }

        $this->form->fill();
    }

    protected function isUserAllowedToAccessPanel(Authenticatable $user): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        return $user->isAdmin() || $user->isSeller();
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'Identifiants incorrects ou compte non autorisé.',
        ]);
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Connexion';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Connexion — MarketPlace';
    }

    public function getSubheading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getSubheading();
        }

        if (! filament()->hasRegistration()) {
            return null;
        }

        return new HtmlString(
            'Pas encore de compte ? '.$this->registerAction->label('Créer un compte')->toHtml()
        );
    }

    protected function getAuthenticateFormAction(): \Filament\Actions\Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Se connecter');
    }
}
