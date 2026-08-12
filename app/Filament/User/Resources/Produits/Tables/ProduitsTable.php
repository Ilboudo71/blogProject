<?php

namespace App\Filament\User\Resources\Produits\Tables;

use App\Models\Product;
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

class ProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->imageHeight(44)
                    ->circular()
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Produit')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('price')
                    ->label('Prix')
                    ->money('XOF')
                    ->sortable(),
                TextColumn::make('type_produits')
                    ->label('Catégorie')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Product::typeLabels()[$state] ?? (string) $state)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === Product::STATUS_PUBLISHED ? 'Publié' : 'Brouillon')
                    ->color(fn (string $state): string => $state === Product::STATUS_PUBLISHED ? 'success' : 'warning'),
                TextColumn::make('views_count')
                    ->label('Vues')
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->sortable()
                    ->alignEnd()
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-m-heart'),
                TextColumn::make('published_at')
                    ->label('Publié le')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        Product::STATUS_DRAFT => 'Brouillon',
                        Product::STATUS_PUBLISHED => 'Publié',
                    ]),
                SelectFilter::make('type_produits')
                    ->label('Catégorie')
                    ->options(Product::typeLabels()),
            ])
            ->recordActions([
                Action::make('publish')
                    ->label('Publier')
                    ->icon('heroicon-o-globe-alt')
                    ->color('success')
                    ->visible(fn (Product $record): bool => ! $record->isPublished())
                    ->requiresConfirmation()
                    ->modalHeading('Publier ce produit ?')
                    ->modalDescription('Le produit sera visible sur la marketplace publique.')
                    ->action(function (Product $record): void {
                        $record->publish();

                        Notification::make()
                            ->title('Produit publié')
                            ->success()
                            ->send();
                    }),
                Action::make('unpublish')
                    ->label('Dépublier')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->visible(fn (Product $record): bool => $record->isPublished())
                    ->requiresConfirmation()
                    ->action(function (Product $record): void {
                        $record->unpublish();

                        Notification::make()
                            ->title('Produit dépublié')
                            ->success()
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
