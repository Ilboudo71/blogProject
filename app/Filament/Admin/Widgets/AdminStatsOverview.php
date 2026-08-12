<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Espace administration';

    protected ?string $description = 'Pilot global de la marketplace MarketPlace';

    protected function getStats(): array
    {
        $published = Product::query()->published()->count();
        $drafts = Product::query()->where('status', Product::STATUS_DRAFT)->count();
        $views = (int) Product::query()->sum('views_count');

        return [
            Stat::make('Vendeurs', User::query()->where('role', 'user')->count())
                ->description('Comptes exposants')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Produits publiés', $published)
                ->description("{$drafts} brouillon(s)")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
            Stat::make('Vues totales', number_format($views, 0, ',', ' '))
                ->description('Sur tous les produits')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
            Stat::make('Administrateurs', User::query()->where('role', 'admin')->count())
                ->description('Comptes admin')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('warning'),
        ];
    }
}
