<?php

namespace App\Filament\User\Resources\Produits\Schemas;

use App\Models\Product;
use App\Support\ProductPhotoUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProduitsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Informations produit'))
                    ->description(__('Renseignez les détails de votre annonce.'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nom du produit'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->label(__('Prix (FCFA)'))
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('FCFA'),
                        Select::make('type_produits')
                            ->label(__('Catégorie'))
                            ->options(Product::typeLabels())
                            ->required()
                            ->searchable(),
                        Select::make('status')
                            ->label(__('Statut'))
                            ->options([
                                Product::STATUS_DRAFT => __('Brouillon'),
                                Product::STATUS_PUBLISHED => __('Publié'),
                            ])
                            ->default(Product::STATUS_DRAFT)
                            ->required()
                            ->helperText(__('Publiez pour exposer le produit sur la marketplace.')),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(4)
                            ->columnSpanFull(),
                        ProductPhotoUpload::make('photo'),
                    ]),
            ]);
    }
}
