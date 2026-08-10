<?php

namespace App\Filament\User\Resources\Produits\Pages;

use App\Filament\User\Resources\Produits\ProduitsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduits extends EditRecord
{
    protected static string $resource = ProduitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
