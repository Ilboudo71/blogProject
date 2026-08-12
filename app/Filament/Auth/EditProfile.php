<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('photo')
                    ->label('Photo de profil')
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->directory('users')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(4096)
                    ->helperText('Ajoutez ou modifiez votre photo de profil.'),
                TextInput::make('first_name')
                    ->label('Prénom')
                    ->required()
                    ->maxLength(255),
                $this->getNameFormComponent()
                    ->label('Nom'),
                $this->getEmailFormComponent()
                    ->label('E-mail'),
                TextInput::make('number_phone')
                    ->label('Contact (téléphone)')
                    ->tel()
                    ->required()
                    ->maxLength(30),
                TextInput::make('locality')
                    ->label('Localité')
                    ->placeholder('Ville, quartier…')
                    ->required()
                    ->maxLength(255),
                $this->getPasswordFormComponent()
                    ->label('Nouveau mot de passe'),
                $this->getPasswordConfirmationFormComponent()
                    ->label('Confirmer le mot de passe'),
                $this->getCurrentPasswordFormComponent()
                    ->label('Mot de passe actuel'),
            ]);
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'Mon profil';
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Mon profil — MarketPlace';
    }
}
