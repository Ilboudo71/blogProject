<?php

namespace App\Filament\Auth\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseNotification
{
    public string $url;

    protected function resetUrl($notifiable): string
    {
        return $this->url;
    }

    protected function buildMailMessage($url): MailMessage
    {
        $expire = (int) config(
            'auth.passwords.'.config('auth.defaults.passwords').'.expire',
            60
        );

        $name = trim((string) ($notifiable->full_name ?? $notifiable->name ?? ''));

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — MarketPlace')
            ->view('emails.password-reset', [
                'url' => $url,
                'expire' => $expire,
                'name' => $name !== '' ? $name : null,
            ]);
    }
}
