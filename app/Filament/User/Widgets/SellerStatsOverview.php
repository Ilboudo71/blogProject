<?php

namespace App\Filament\User\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SellerStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Espace vendeur';

    protected ?string $description = 'Exposez, publiez et suivez la visibilité de vos annonces';

    protected function getStats(): array
    {
        $userId = Auth::id();

        $total = Product::query()->where('user_id', $userId)->count();
        $published = Product::query()->where('user_id', $userId)->published()->count();
        $drafts = Product::query()->where('user_id', $userId)->where('status', Product::STATUS_DRAFT)->count();
        $views = (int) Product::query()->where('user_id', $userId)->sum('views_count');
        $likes = (int) Product::query()->where('user_id', $userId)->sum('likes_count');

        return [
            Stat::make('Total produits', $total)
                ->description('Dans votre catalogue')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            Stat::make('Publiés', $published)
                ->description('Visibles sur la marketplace')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('success'),
            Stat::make('Brouillons', $drafts)
                ->description('Non exposés pour le moment')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),
            Stat::make('Vues totales', number_format($views, 0, ',', ' '))
                ->description('Suivi de visibilité')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
            Stat::make('Likes totaux', number_format($likes, 0, ',', ' '))
                ->description('Cœurs reçus sur vos produits')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),
        ];
    }
}
