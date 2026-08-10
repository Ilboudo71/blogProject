<?php

namespace App\Filament\User\Resources\Produits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class ProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable(),
                ImageColumn::make('photo')
                ->imageHeight(40)
                ->circular()->disk('public'),
                TextColumn::make('name')->label("Nom du produit")->searchable()->sortable(),
                TextColumn::make('price')->sortable(),
                TextColumn::make('type_produits')->label("Type de produits")->searchable(),
                TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
             
            ])
            ->filters([
                 SelectFilter::make('type_produits')
                ->options([
                    'hygiene' => 'Produits d\'hygiène',
                    'alimentaire' => 'Produits alimentaires',
                    'electronique' => 'Électronique',
                    'vetement' => 'Vêtements',
                    'autres'=> 'Autres types de produits',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }
}


