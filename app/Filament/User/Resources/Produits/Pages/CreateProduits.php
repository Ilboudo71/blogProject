<?php

namespace App\Filament\User\Resources\Produits\Pages;

use App\Filament\User\Resources\Produits\ProduitsResource;
use App\Models\Product;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CreateProduits extends CreateRecord
{
    protected static string $resource = ProduitsResource::class;

    public function mount(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->canCreateProduct()) {
            Notification::make()
                ->title('Limite de produit atteinte')
                ->body('Votre compte gratuit est limité à 1 produit. Passez au statut Premium (5 050 FCFA/an) pour publier des produits en illimité.')
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

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
