<?php

namespace App\Support;

use Filament\Forms\Components\ViewField;

class ProductPhotoUpload
{
    /**
     * Champ photo produit : aperçu + bouton Modifier, upload HTTP natif (sans Livewire).
     */
    public static function make(string $name = 'photo'): ViewField
    {
        return ViewField::make($name)
            ->label(__('Photo'))
            ->view('filament.forms.product-photo-field')
            ->viewData(fn (ViewField $component): array => [
                'productId' => $component->getRecord()?->getKey(),
            ])
            ->required()
            ->validationMessages([
                'required' => __('Veuillez ajouter une photo du produit.'),
            ])
            ->helperText(__('Utilisez le bouton Modifier pour changer la photo rapidement.'))
            ->columnSpanFull();
    }
}
