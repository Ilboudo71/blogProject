<?php

namespace App\Filament\User\Widgets;

use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SellerStatsOverview extends StatsOverviewWidget
{
    public function getHeading(): ?string
    {
        return __('Espace vendeur');
    }

    public function getDescription(): ?string
    {
        return __('Exposez, publiez et suivez la visibilité de vos annonces');
    }

    protected function getStats(): array
    {
        /** @var User|null $user */
        $user = Auth::user();
        $userId = $user?->id;

        $total = Product::query()->where('user_id', $userId)->count();
        $published = Product::query()->where('user_id', $userId)->published()->count();
        $drafts = Product::query()->where('user_id', $userId)->where('status', Product::STATUS_DRAFT)->count();
        $views = (int) Product::query()->where('user_id', $userId)->sum('views_count');
        $likes = (int) Product::query()->where('user_id', $userId)->sum('likes_count');

        $isPremium = $user?->isPremium() ?? false;
        $premiumDesc = $isPremium
            ? ($user?->premium_expires_at ? __('Expire le') . ' ' . $user->premium_expires_at->format('d/m/Y') : __('Illimité'))
            : __('Limité à 1 produit');

        return [
            Stat::make(__('Statut Compte'), $isPremium ? __('Vendeur Premium') : __('Standard Gratuit'))
                ->description($premiumDesc)
                ->descriptionIcon($isPremium ? 'heroicon-m-sparkles' : 'heroicon-m-user')
                ->color($isPremium ? 'success' : 'gray'),
            Stat::make(__('Total produits'), $total)
                ->description(__('Dans votre catalogue'))
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            Stat::make(__('Publiés'), $published)
                ->description(__('Visibles sur la marketplace'))
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('success'),
            Stat::make(__('Brouillons'), $drafts)
                ->description(__('Non exposés pour le moment'))
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),
            Stat::make(__('Vues totales'), number_format($views, 0, ',', ' '))
                ->description(__('Suivi de visibilité'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
            Stat::make(__('Likes totaux'), number_format($likes, 0, ',', ' '))
                ->description(__('Cœurs reçus sur vos produits'))
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),
        ];
    }
}
