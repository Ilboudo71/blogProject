<?php

namespace App\Support;

use App\Filament\Forms\Components\ProductPhotoField;

class ProductPhotoUpload
{
    /**
     * Champ photo produit : aperçu + bouton Modifier, upload HTTP natif.
     */
    public static function make(string $name = 'photo'): ProductPhotoField
    {
        return ProductPhotoField::make($name)
            ->label(__('Photo'))
            ->required()
            ->validationMessages([
                'required' => __('Veuillez ajouter une photo du produit.'),
            ])
            ->helperText(__('Utilisez le bouton Modifier la photo pour changer l’image rapidement.'))
            ->columnSpanFull();
    }
}
