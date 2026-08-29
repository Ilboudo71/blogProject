<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->label('Prénom')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('number_phone')
                            ->label('Contact (téléphone)')
                            ->tel()
                            ->required(),
                        TextInput::make('locality')
                            ->label('Localité')
                            ->placeholder('Ville, quartier…')
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->label('Rôle')
                            ->options([
                                'admin' => 'Administrateur',
                                'user' => 'Vendeur',
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText(fn (string $operation): ?string => $operation === 'edit'
                                ? 'Laissez vide pour conserver le mot de passe actuel.'
                                : null),
                        FileUpload::make('photo')
                            ->label('Photo')
                            ->image()
                            ->directory('users')
                            ->disk('public')
                            ->visibility('public')
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpanFull(),
                    ]),

                Section::make('Abonnement Premium')
                    ->description('Gestion du statut Premium pour autoriser la publication illimitée de produits.')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_premium')
                            ->label('Statut Premium actif')
                            ->helperText('Permet de publier un nombre illimité de produits.')
                            ->live()
                            ->afterStateUpdated(function (bool $state, callable $set, callable $get): void {
                                if ($state) {
                                    if (! $get('premium_activated_at')) {
                                        $set('premium_activated_at', now());
                                    }
                                    if (! $get('premium_expires_at')) {
                                        $set('premium_expires_at', now()->addYear());
                                    }
                                }
                            }),
                        TextInput::make('premium_payment_ref')
                            ->label('Référence de paiement (Orange Money)')
                            ->placeholder('Ex: OM TX123456...')
                            ->maxLength(255),
                        DateTimePicker::make('premium_activated_at')
                            ->label('Date d’activation')
                            ->native(false),
                        DateTimePicker::make('premium_expires_at')
                            ->label('Date d’expiration (12 mois)')
                            ->native(false),
                    ]),
            ]);
    }
}
