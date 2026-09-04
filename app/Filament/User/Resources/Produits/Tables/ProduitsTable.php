<?php

namespace App\Filament\User\Resources\Produits\Tables;

use App\Models\Product;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('photo')
                    ->label(__('Photo'))
                    ->imageHeight(44)
                    ->circular()
                    ->disk('public'),
                TextColumn::make('name')
                    ->label(__('Produit'))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('price')
                    ->label(__('Prix'))
                    ->money('XOF')
                    ->sortable(),
                TextColumn::make('type_produits')
                    ->label(__('Catégorie'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Product::typeLabels()[$state] ?? (string) $state)
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Statut'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === Product::STATUS_PUBLISHED ? __('Publié') : __('Brouillon'))
                    ->color(fn (string $state): string => $state === Product::STATUS_PUBLISHED ? 'success' : 'warning'),
                TextColumn::make('views_count')
                    ->label(__('Vues'))
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('likes_count')
                    ->label(__('Likes'))
                    ->sortable()
                    ->alignEnd()
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-m-heart'),
                TextColumn::make('published_at')
                    ->label(__('Publié le'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('Créé le'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Statut'))
                    ->options([
                        Product::STATUS_DRAFT => __('Brouillon'),
                        Product::STATUS_PUBLISHED => __('Publié'),
                    ]),
                SelectFilter::make('type_produits')
                    ->label(__('Catégorie'))
                    ->options(Product::typeLabels()),
            ])
            ->recordActions([
                Action::make('publish')
                    ->label(__('Publier'))
                    ->icon('heroicon-o-globe-alt')
                    ->color('success')
                    ->visible(fn (Product $record): bool => ! $record->isPublished())
                    ->requiresConfirmation(fn (): bool => (bool) Auth::user()?->canPublishMoreProducts())
                    ->modalWidth('2xl')
                    ->modalHeading(fn (): string => Auth::user()?->canPublishMoreProducts()
                        ? __('Publier ce produit ?')
                        : __('Abonnement Premium requis'))
                    ->modalDescription(fn (): ?string => Auth::user()?->canPublishMoreProducts()
                        ? __('Le produit sera visible sur la marketplace publique.')
                        : __('Votre compte gratuit est limité à 1 seul produit publié. Passez en statut Premium (5 050 FCFA/an) pour publier des produits en illimité toute l\'année.'))
                    ->modalContent(fn (): ?\Illuminate\Contracts\View\View => Auth::user()?->canPublishMoreProducts()
                        ? null
                        : view('filament.modals.premium-info'))
                    ->modalSubmitAction(fn ($action) => Auth::user()?->canPublishMoreProducts() ? $action : false)
                    ->modalCancelActionLabel(fn (): string => Auth::user()?->canPublishMoreProducts() ? __('Annuler') : __('Fermer'))
                    ->action(function (Product $record): void {
                        /** @var User|null $user */
                        $user = Auth::user();

                        if ($user && ! $user->canPublishMoreProducts()) {
                            Notification::make()
                                ->title(__('Publication impossible'))
                                ->body(__('Votre compte gratuit est limité à 1 produit publié. Passez en Premium (5 050 FCFA/an) pour publier des produits en illimité.'))
                                ->warning()
                                ->send();

                            return;
                        }

                        $record->publish();

                        Notification::make()
                            ->title(__('Produit publié'))
                            ->body(__('Votre produit est désormais visible par tous sur la marketplace.'))
                            ->success()
                            ->send();
                    }),
                ViewAction::make()->label(__('Voir')),
                Action::make('change_photo')
                    ->label(__('Modifier la photo'))
                    ->icon('heroicon-o-camera')
                    ->color('primary')
                    ->modalHeading(__('Modifier la photo du produit'))
                    ->modalDescription(__('Choisissez une nouvelle image. Elle sera enregistrée immédiatement.'))
                    ->modalContent(fn (Product $record): \Illuminate\Contracts\View\View => view('filament.modals.change-product-photo', [
                        'product' => $record,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('Fermer'))
                    ->modalWidth('md'),
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
