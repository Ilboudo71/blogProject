<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('photo')
                    ->label(__('Photo'))
                    ->imageHeight(40)
                    ->circular()
                    ->disk('public'),
                TextColumn::make('first_name')
                    ->label(__('Prénom'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('Nom'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('E-mail'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('number_phone')
                    ->label(__('Contact'))
                    ->searchable(),
                TextColumn::make('locality')
                    ->label(__('Localité'))
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('role')
                    ->label(__('Rôle'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'admin' ? __('Administrateur') : __('Vendeur'))
                    ->color(fn (string $state): string => $state === 'admin' ? 'danger' : 'success'),
                TextColumn::make('is_premium')
                    ->label(__('Premium'))
                    ->badge()
                    ->state(function (User $record): string {
                        if ($record->isPremium()) {
                            return __('Activé');
                        }
                        if ($record->isPremiumExpired()) {
                            return __('Expiré');
                        }

                        return __('Désactivé');
                    })
                    ->description(function (User $record): ?string {
                        if ($record->isPremium() && $record->premium_expires_at) {
                            return __('Jusqu’au') . ' ' . $record->premium_expires_at->format('d/m/Y');
                        }
                        if ($record->isPremiumExpired() && $record->premium_expires_at) {
                            return __('Expiré le') . ' ' . $record->premium_expires_at->format('d/m/Y');
                        }

                        return null;
                    })
                    ->color(function (User $record): string {
                        if ($record->isPremium()) {
                            return 'success';
                        }
                        if ($record->isPremiumExpired()) {
                            return 'warning';
                        }

                        return 'gray';
                    })
                    ->icon(function (User $record): ?string {
                        if ($record->isPremium()) {
                            return 'heroicon-m-sparkles';
                        }

                        return null;
                    })
                    ->sortable(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label(__('Produits'))
                    ->alignEnd(),
                TextColumn::make('created_at')
                    ->label(__('Inscrit le'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('Rôle'))
                    ->options([
                        'admin' => __('Administrateur'),
                        'user' => __('Vendeur'),
                    ]),
                SelectFilter::make('premium_status')
                    ->label(__('Statut Premium'))
                    ->options([
                        'active' => __('Premium Activé'),
                        'expired' => __('Premium Expiré'),
                        'inactive' => __('Premium Désactivé / Standard'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'active' => $query->where('is_premium', true)
                                ->where(fn (Builder $q) => $q->whereNull('premium_expires_at')->orWhere('premium_expires_at', '>', now())),
                            'expired' => $query->where('is_premium', true)
                                ->whereNotNull('premium_expires_at')
                                ->where('premium_expires_at', '<=', now()),
                            'inactive' => $query->where(fn (Builder $q) => $q->where('is_premium', false)->orWhereNull('is_premium')),
                            default => $query,
                        };
                    }),
                SelectFilter::make('year')
                    ->label(__('Année'))
                    ->options([
                        '2024' => '2024',
                        '2025' => '2025',
                        '2026' => '2026',
                        '2027' => '2027',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->whereYear('created_at', $data['value']);
                    }),
            ])
            ->recordActions([
                Action::make('activate_premium')
                    ->label(__('Activer Premium'))
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->visible(fn (User $record): bool => ! $record->isPremium())
                    ->modalHeading(fn (User $record): string => __('Activer l\'abonnement Premium pour :name', ['name' => $record->full_name]))
                    ->modalDescription(__('L\'abonnement sera actif pour 12 mois à compter d\'aujourd\'hui.'))
                    ->form([
                        DateTimePicker::make('premium_expires_at')
                            ->label(__('Date d’expiration de l’abonnement (12 mois)'))
                            ->default(now()->addYear())
                            ->required()
                            ->native(false),
                        TextInput::make('premium_payment_ref')
                            ->label(__('Référence ou preuve de paiement Orange Money'))
                            ->placeholder('Ex: OM TX94827492... (74 31 61 53)')
                            ->maxLength(255),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->activatePremium($data['premium_expires_at'], $data['premium_payment_ref'] ?? null);

                        Notification::make()
                            ->title(__('Statut Premium activé'))
                            ->body(__('L\'utilisateur :name peut désormais publier des produits en illimité.', ['name' => $record->full_name]))
                            ->success()
                            ->send();
                    }),
                Action::make('deactivate_premium')
                    ->label(__('Désactiver Premium'))
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->visible(fn (User $record): bool => $record->isPremium())
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record): string => __('Désactiver le statut Premium de :name ?', ['name' => $record->full_name]))
                    ->modalDescription(__('L’utilisateur perdra l’accès illimité et sera à nouveau limité à 1 produit pour ses futures publications. Ses produits existants ne seront pas supprimés.'))
                    ->action(function (User $record): void {
                        $record->deactivatePremium();

                        Notification::make()
                            ->title(__('Statut Premium désactivé'))
                            ->body(__('L\'utilisateur :name est repassé au statut standard.', ['name' => $record->full_name]))
                            ->warning()
                            ->send();
                    }),
                ViewAction::make()->label(__('Voir')),
                EditAction::make()->label(__('Modifier')),
                DeleteAction::make()->label(__('Supprimer')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
