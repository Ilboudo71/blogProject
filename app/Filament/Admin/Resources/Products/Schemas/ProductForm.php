<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Informations produit'))
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
                            ->required(),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(4)
                            ->columnSpanFull(),
                        FileUpload::make('photo')
                            ->label(__('Photo'))
                            ->image()
                            ->directory('products')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
