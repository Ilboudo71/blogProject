<?php

namespace App\Filament\User\Resources\Produits\Pages;

use App\Filament\User\Resources\Produits\ProduitsResource;
use App\Models\Product;
use App\Models\User;
use Filament\Actions\Action;
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
            Action::make('change_photo')
                ->label(__('Modifier la photo'))
                ->icon('heroicon-o-camera')
                ->color('primary')
                ->modalHeading(__('Modifier la photo du produit'))
                ->modalDescription(__('Choisissez une nouvelle image. Elle sera enregistrée immédiatement.'))
                ->modalContent(fn (): \Illuminate\Contracts\View\View => view('filament.modals.change-product-photo', [
                    'product' => $this->getRecord(),
                ]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel(__('Fermer'))
                ->modalWidth('md'),
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
