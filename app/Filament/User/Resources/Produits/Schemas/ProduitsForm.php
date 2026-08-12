<?php

namespace App\Filament\User\Resources\Produits\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
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
                Section::make('Informations produit')
                    ->description('Renseignez les détails de votre annonce.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom du produit')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->label('Prix (FCFA)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('FCFA'),
                        Select::make('type_produits')
                            ->label('Catégorie')
                            ->options(Product::typeLabels())
                            ->required()
                            ->searchable(),
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                Product::STATUS_DRAFT => 'Brouillon',
                                Product::STATUS_PUBLISHED => 'Publié',
                            ])
                            ->default(Product::STATUS_DRAFT)
                            ->required()
                            ->helperText('Publiez pour exposer le produit sur la marketplace.'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->columnSpanFull(),
                        FileUpload::make('photo')
                            ->label('Photo')
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
