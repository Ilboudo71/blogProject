<?php

namespace App\Support;

use Filament\Forms\Components\FileUpload;

class ProductPhotoUpload
{
    /**
     * Champ photo produit fiable (création + modification).
     */
    public static function make(string $name = 'photo'): FileUpload
    {
        return FileUpload::make($name)
            ->label(__('Photo'))
            ->image()
            ->disk('public')
            ->directory('products')
            ->visibility('public')
            ->acceptedFileTypes([
                'image/jpeg',
                'image/jpg',
                'image/pjpeg',
                'image/png',
                'image/webp',
                'image/gif',
                'image/bmp',
                'image/x-ms-bmp',
                'image/svg+xml',
                'image/heic',
                'image/heif',
                'image/avif',
                'image/*',
            ])
            ->maxSize(20480)
            ->imagePreviewHeight('280')
            ->openable()
            ->downloadable()
            ->deletable()
            ->moveFiles()
            ->required()
            ->helperText(__('Formats acceptés : JPG, PNG, WEBP, GIF, BMP, HEIC… Cliquez pour ajouter ou remplacer la photo (max. 20 Mo).'))
            ->columnSpanFull();
    }
}
