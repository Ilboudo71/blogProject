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
                Section::make(__('Profil'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('Prénom'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label(__('Nom'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('E-mail'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('locale')
                            ->label(__('Langue'))
                            ->options([
                                'fr' => 'Français',
                                'en' => 'English',
                            ])
                            ->default('fr')
                            ->required(),
                        TextInput::make('number_phone')
                            ->label(__('Contact (téléphone)'))
                            ->tel()
                            ->required(),
                        TextInput::make('locality')
                            ->label(__('Localité'))
                            ->placeholder(__('Ville, quartier…'))
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->label(__('Rôle'))
                            ->options([
                                'admin' => __('Administrateur'),
                                'user' => __('Vendeur'),
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('password')
                            ->label(__('Mot de passe'))
                            ->password()
                            ->revealable()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText(fn (string $operation): ?string => $operation === 'edit'
                                ? __('Laissez vide pour conserver le mot de passe actuel.')
                                : null),
                        FileUpload::make('photo')
                            ->label(__('Photo'))
                            ->image()
                            ->directory('users')
                            ->disk('public')
                            ->visibility('public')
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Abonnement Premium'))
                    ->description(__('Gestion du statut Premium pour autoriser la publication illimitée de produits.'))
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_premium')
                            ->label(__('Statut Premium actif'))
                            ->helperText(__('Permet de publier un nombre illimité de produits.'))
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
                            ->label(__('Référence de paiement (Orange Money)'))
                            ->placeholder('Ex: OM TX123456...')
                            ->maxLength(255),
                        DateTimePicker::make('premium_activated_at')
                            ->label(__('Date d’activation'))
                            ->native(false),
                        DateTimePicker::make('premium_expires_at')
                            ->label(__('Date d’expiration (12 mois)'))
                            ->native(false),
                    ]),
            ]);
    }
}
