<?php

namespace App\Filament\Admin\Resources\Products\Pages;

use App\Filament\Admin\Resources\Products\ProductResource;
use App\Models\Product;
use App\Support\ProductPhotoService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label(__('Supprimer')),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var Product $record */
        $record = $this->record;

        if (blank($data['photo'] ?? null)) {
            unset($data['photo']);
        }

        unset($data['photo_data'], $data['photo_mime']);

        if (($data['status'] ?? null) === Product::STATUS_PUBLISHED && ! $record->published_at) {
            $data['published_at'] = Carbon::now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Product $record */
        $record = $this->record;
        $path = is_string($record->photo) ? $record->photo : '';

        if ($path !== '') {
            $pending = Cache::pull('pending_product_photo:'.$path);
            if (is_array($pending) && filled($pending['data'] ?? null)) {
                $record->forceFill([
                    'photo_mime' => $pending['mime'] ?? 'image/jpeg',
                    'photo_data' => $pending['data'],
                ])->save();

                return;
            }
        }

        app(ProductPhotoService::class)->persistDiskPhotoToDatabase($record);
    }
}
