<?php

namespace App\Filament\User\Resources\Produits\Pages;

use App\Filament\User\Resources\Produits\ProduitsResource;
use App\Models\Product;
use App\Models\User;
use App\Support\ProductPhotoService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CreateProduits extends CreateRecord
{
    protected static string $resource = ProduitsResource::class;

    public function mount(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->canCreateProduct()) {
            Notification::make()
                ->title(__('Limite de produit atteinte'))
                ->body(__('Votre compte gratuit est limité à 1 produit. Passez au statut Premium (5 050 FCFA/an) pour publier des produits en illimité.'))
                ->warning()
                ->persistent()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));

            return;
        }

        parent::mount();
    }

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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
