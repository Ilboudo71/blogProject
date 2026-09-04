<?php

namespace App\Filament\User\Resources\Produits\Pages;

use App\Filament\User\Resources\Produits\ProduitsResource;
use App\Models\Product;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EditProduits extends EditRecord
{
    protected static string $resource = ProduitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Supprimer'),
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
                    ->title('Publication impossible')
                    ->body('Votre compte gratuit est limité à 1 produit publié. Passez au statut Premium (5 050 FCFA/an) pour publier des produits supplémentaires.')
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

        // Ne pas effacer la photo existante si le champ revient vide pendant l'édition.
        if (blank($data['photo'] ?? null)) {
            unset($data['photo']);
        }

        if (($data['status'] ?? null) === Product::STATUS_PUBLISHED && ! $record->published_at) {
            $data['published_at'] = Carbon::now();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
