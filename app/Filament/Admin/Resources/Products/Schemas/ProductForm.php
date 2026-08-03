<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label("Nom du produit")->required(),
                TextInput::make('price')->numeric()->label("Prix")->required(),
                TextInput::make('description')->label("Description"),

                Select::make('type_produits')
                    ->options([
                        'hygiene' => 'Produits d\'hygiène',
                        'alimentaire' => 'Produits alimentaires',
                        'electronique' => 'Électronique',
                        'vetement' => 'Vêtements',
                        'autres'=> 'Autres types de produits',
                    ])
                    ->required()->searchable(),

                FileUpload::make('photo')
                        ->image()
                        ->directory('products')
                        ->disk('public')->required(),


                
            ]);
    }
}
