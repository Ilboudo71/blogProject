<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Statistiques de la plateforme';

    protected ?string $description = 'Indicateurs d’activité et suivi des abonnements Premium Raaga';

    protected function getStats(): array
    {
        $totalUsers = User::count();

        $premiumUsers = User::query()
            ->where('is_premium', true)
            ->where(function (Builder $q) {
                $q->whereNull('premium_expires_at')
                    ->orWhere('premium_expires_at', '>', now());
            })
            ->count();

        $nonPremiumUsers = max(0, $totalUsers - $premiumUsers);

        $premiumPercentage = $totalUsers > 0
            ? round(($premiumUsers / $totalUsers) * 100, 1)
            : 0;

        $nonPremiumPercentage = $totalUsers > 0
            ? round(($nonPremiumUsers / $totalUsers) * 100, 1)
            : 0;

        $sellersCount = User::query()->where('role', 'user')->count();
        $publishedProducts = Product::query()->published()->count();
        $draftProducts = Product::query()->where('status', Product::STATUS_DRAFT)->count();
        $totalViews = (int) Product::query()->sum('views_count');

        return [
            Stat::make('Total utilisateurs', $totalUsers)
                ->description("{$sellersCount} vendeur(s) inscrit(s)")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Utilisateurs Premium', "{$premiumUsers} ({$premiumPercentage}%)")
                ->description('Abonnements actifs (5 050 FCFA/an)')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('success'),

            Stat::make('Utilisateurs non-Premium', "{$nonPremiumUsers} ({$nonPremiumPercentage}%)")
                ->description('Comptes standards limités à 1 produit')
                ->descriptionIcon('heroicon-m-user-minus')
                ->color('gray'),

            Stat::make('Produits publiés', $publishedProducts)
                ->description("{$draftProducts} produit(s) en brouillon")
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('info'),

            Stat::make('Vues totales', number_format($totalViews, 0, ',', ' '))
                ->description('Sur l\'ensemble des produits exposés')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
        ];
    }
}
