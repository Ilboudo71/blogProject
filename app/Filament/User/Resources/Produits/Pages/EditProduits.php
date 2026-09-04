<?php

namespace App\Filament\User\Resources\Produits\Pages;

use App\Filament\User\Resources\Produits\ProduitsResource;
use App\Models\Product;
use App\Models\User;
use App\Support\ProductPhotoService;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EditProduits extends EditRecord
{
    protected static string $resource = ProduitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label(__('Supprimer')),
        ];
    }

    protected function beforeSave(): void
    {
        /** @var Product $record */
        $record = $this->record;
        /** @var User|null $user */
        $user = Auth::user();

        $newStatus = $this->data['status'] ?? null;

        if ($newStatus === Product::STATUS_PUBLISHED && ! $record->isPublished()) {
            if ($user && ! $user->canPublishMoreProducts()) {
                Notification::make()
                    ->title(__('Publication impossible'))
                    ->body(__('Votre compte gratuit est limité à 1 produit publié. Passez au statut Premium (5 050 FCFA/an) pour publier des produits supplémentaires.'))
                    ->danger()
                    ->persistent()
                    ->send();

                $this->halt();
            }
        }
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
