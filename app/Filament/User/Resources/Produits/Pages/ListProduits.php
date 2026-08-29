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
                ->label('Passer en Premium (5 050 FCFA/an)')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->modalWidth('lg')
                ->modalHeading('Abonnement Premium annuel')
                ->modalDescription('Débloquez la publication illimitée de produits sur Raaga.')
                ->modalContent(view('filament.modals.premium-info'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fermer');
        }

        if ($canCreate) {
            $actions[] = CreateAction::make()
                ->label('Nouveau produit');
        } else {
            $actions[] = Action::make('create_blocked')
                ->label('Nouveau produit (Limite atteinte)')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->modalWidth('lg')
                ->modalHeading('Limite de produit atteinte')
                ->modalDescription('Votre compte gratuit vous autorise à publier 1 produit. Passez en Premium pour publier des produits en illimité.')
                ->modalContent(view('filament.modals.premium-info'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fermer');
        }

        return $actions;
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Mes produits';
    }
}
