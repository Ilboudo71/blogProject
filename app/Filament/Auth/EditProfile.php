<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('photo')
                    ->label(__('Photo de profil'))
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->directory('users')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(4096)
                    ->helperText(__('Ajoutez ou modifiez votre photo de profil.')),
                TextInput::make('first_name')
                    ->label(__('Prénom'))
                    ->required()
                    ->maxLength(255),
                $this->getNameFormComponent()
                    ->label(__('Nom')),
                $this->getEmailFormComponent()
                    ->label(__('E-mail')),
                Select::make('locale')
                    ->label(__('Langue'))
                    ->options([
                        'fr' => 'Français (French)',
                        'en' => 'English (Anglais)',
                    ])
                    ->default('fr')
                    ->required(),
                TextInput::make('number_phone')
                    ->label(__('Contact (téléphone)'))
                    ->tel()
                    ->required()
                    ->maxLength(30),
                TextInput::make('locality')
                    ->label(__('Localité'))
                    ->placeholder(__('Ville, quartier…'))
                    ->required()
                    ->maxLength(255),
                $this->getPasswordFormComponent()
                    ->label(__('Nouveau mot de passe')),
                $this->getPasswordConfirmationFormComponent()
                    ->label(__('Confirmer le mot de passe')),
                $this->getCurrentPasswordFormComponent()
                    ->label(__('Mot de passe actuel')),
            ]);
    }

    protected function afterSave(): void
    {
        $user = $this->getUser();
        if ($user?->locale && in_array($user->locale, ['fr', 'en'], true)) {
            session(['locale' => $user->locale]);
        }
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return __('Mon profil');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('Mon profil') . ' — Raaga';
    }
}
