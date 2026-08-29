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
                    ->label('Photo')
                    ->imageHeight(40)
                    ->circular()
                    ->disk('public'),
                TextColumn::make('first_name')
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('number_phone')
                    ->label('Contact')
                    ->searchable(),
                TextColumn::make('locality')
                    ->label('Localité')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'admin' ? 'Administrateur' : 'Vendeur')
                    ->color(fn (string $state): string => $state === 'admin' ? 'danger' : 'success'),
                TextColumn::make('is_premium')
                    ->label('Premium')
                    ->badge()
                    ->state(function (User $record): string {
                        if ($record->isPremium()) {
                            return 'Activé';
                        }
                        if ($record->isPremiumExpired()) {
                            return 'Expiré';
                        }

                        return 'Désactivé';
                    })
                    ->description(function (User $record): ?string {
                        if ($record->isPremium() && $record->premium_expires_at) {
                            return 'Jusqu’au '.$record->premium_expires_at->format('d/m/Y');
                        }
                        if ($record->isPremiumExpired() && $record->premium_expires_at) {
                            return 'Expiré le '.$record->premium_expires_at->format('d/m/Y');
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
                    ->label('Produits')
                    ->alignEnd(),
                TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'admin' => 'Administrateur',
                        'user' => 'Vendeur',
                    ]),
                SelectFilter::make('premium_status')
                    ->label('Statut Premium')
                    ->options([
                        'active' => 'Premium Activé',
                        'expired' => 'Premium Expiré',
                        'inactive' => 'Premium Désactivé / Standard',
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
                    ->label('Année')
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
                    ->label('Activer Premium')
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->visible(fn (User $record): bool => ! $record->isPremium())
                    ->modalHeading(fn (User $record): string => "Activer l'abonnement Premium pour {$record->full_name}")
                    ->modalDescription('L\'abonnement sera actif pour 12 mois à compter d\'aujourd\'hui.')
                    ->form([
                        DateTimePicker::make('premium_expires_at')
                            ->label('Date d’expiration de l’abonnement (12 mois)')
                            ->default(now()->addYear())
                            ->required()
                            ->native(false),
                        TextInput::make('premium_payment_ref')
                            ->label('Référence ou preuve de paiement Orange Money')
                            ->placeholder('Ex: OM TX94827492... (74 31 61 53)')
                            ->maxLength(255),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->activatePremium($data['premium_expires_at'], $data['premium_payment_ref'] ?? null);

                        Notification::make()
                            ->title('Statut Premium activé')
                            ->body("L'utilisateur {$record->full_name} peut désormais publier des produits en illimité.")
                            ->success()
                            ->send();
                    }),
                Action::make('deactivate_premium')
                    ->label('Désactiver Premium')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->visible(fn (User $record): bool => $record->isPremium())
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record): string => "Désactiver le statut Premium de {$record->full_name} ?")
                    ->modalDescription('L’utilisateur perdra l’accès illimité et sera à nouveau limité à 1 produit pour ses futures publications. Ses produits existants ne seront pas supprimés.')
                    ->action(function (User $record): void {
                        $record->deactivatePremium();

                        Notification::make()
                            ->title('Statut Premium désactivé')
                            ->body("L'utilisateur {$record->full_name} est repassé au statut standard.")
                            ->warning()
                            ->send();
                    }),
                ViewAction::make()->label('Voir'),
                EditAction::make()->label('Modifier'),
                DeleteAction::make()->label('Supprimer'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
