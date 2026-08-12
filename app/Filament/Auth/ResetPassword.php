<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\PasswordReset\ResetPassword as BaseResetPassword;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\Rules\Password as PasswordRule;
use SensitiveParameter;

class ResetPassword extends BaseResetPassword
{
    public function mount(?string $email = null, #[SensitiveParameter] ?string $token = null): void
    {
        parent::mount($email, $token);

        $this->email = $email ?? request()->query('email');
        $this->token = $token ?? request()->query('token') ?? $this->token;
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Nouveau mot de passe';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Réinitialiser le mot de passe — MarketPlace';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('E-mail')
            ->disabled()
            ->autofocus(false);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Nouveau mot de passe')
            ->password()
            ->autocomplete('new-password')
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(PasswordRule::default())
            ->same('passwordConfirmation')
            ->validationAttribute('mot de passe');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Confirmer le mot de passe')
            ->password()
            ->autocomplete('new-password')
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    public function getResetPasswordFormAction(): \Filament\Actions\Action
    {
        return parent::getResetPasswordFormAction()
            ->label('Enregistrer le nouveau mot de passe');
    }
}
