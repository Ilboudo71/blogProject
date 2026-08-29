<?php

namespace App\Filament\User\Resources\Produits\Pages;

use App\Filament\User\Resources\Produits\ProduitsResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListProduits extends ListRecords
{
    protected static string $resource = ProduitsResource::class;

    protected function getHeaderActions(): array
    {
        /** @var User|null $user */
        $user = Auth::user();
        $canCreate = $user?->canCreateProduct() ?? false;
        $isPremium = $user?->isPremium() ?? false;

        $actions = [];

        if (! $isPremium) {
            $actions[] = Action::make('upgrade_premium')
                ->label(__('Passer en Premium (5 050 FCFA/an)'))
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->modalWidth('2xl')
                ->modalHeading(__('Abonnement Premium annuel'))
                ->modalDescription(__('Débloquez la publication illimitée de vos produits sur Raaga.'))
                ->modalContent(view('filament.modals.premium-info'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel(__('Fermer'));
        }

        if ($canCreate) {
            $actions[] = CreateAction::make()
                ->label(__('Nouveau produit'));
        } else {
            $actions[] = Action::make('create_blocked')
                ->label(__('Nouveau produit (Limite atteinte)'))
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->modalWidth('2xl')
                ->modalHeading(__('Limite de produit atteinte'))
                ->modalDescription(__('Votre compte gratuit vous autorise à publier 1 seul produit. Passez en Premium pour publier en illimité toute l\'année.'))
                ->modalContent(view('filament.modals.premium-info'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel(__('Fermer'));
        }

        return $actions;
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('Mes produits');
    }
}
