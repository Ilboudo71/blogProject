<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    public function getHeading(): string|Htmlable|null
    {
        return 'Mot de passe oublié ?';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Réinitialiser le mot de passe — MarketPlace';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('E-mail')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getRequestFormAction(): \Filament\Actions\Action
    {
        return parent::getRequestFormAction()
            ->label('Envoyer le lien de réinitialisation');
    }
}
