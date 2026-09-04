<?php

namespace App\Filament\Admin\Resources\Products\Pages;

use App\Filament\Admin\Resources\Products\ProductResource;
use App\Models\Product;
use App\Support\ProductPhotoService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        if (($data['status'] ?? Product::STATUS_DRAFT) === Product::STATUS_PUBLISHED) {
            $data['published_at'] = Carbon::now();
        }

        unset($data['photo_data'], $data['photo_mime']);

        return $data;
    }

    protected function afterCreate(): void
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
