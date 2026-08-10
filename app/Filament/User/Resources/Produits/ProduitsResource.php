<?php

namespace App\Filament\User\Resources\Produits;

use App\Filament\User\Resources\Produits\Pages\CreateProduits;
use App\Filament\User\Resources\Produits\Pages\EditProduits;
use App\Filament\User\Resources\Produits\Pages\ListProduits;
use App\Filament\User\Resources\Produits\Schemas\ProduitsForm;
use App\Filament\User\Resources\Produits\Tables\ProduitsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProduitsResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ProductUser';

    public static function form(Schema $schema): Schema
    {
        return ProduitsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProduits::route('/'),
            'create' => CreateProduits::route('/create'),
            'edit' => EditProduits::route('/{record}/edit'),
        ];
    }
}
