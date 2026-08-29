<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class AdminStatsOverview extends StatsOverviewWidget
{
    public function getHeading(): ?string
    {
        return __('Statistiques de la plateforme');
    }

    public function getDescription(): ?string
    {
        return __('Indicateurs d’activité et suivi des abonnements Premium Raaga');
    }

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
            Stat::make(__('Total utilisateurs'), $totalUsers)
                ->description("{$sellersCount} " . __('vendeur(s) inscrit(s)'))
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make(__('Utilisateurs Premium'), "{$premiumUsers} ({$premiumPercentage}%)")
                ->description(__('Abonnements actifs (5 050 FCFA/an)'))
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('success'),

            Stat::make(__('Utilisateurs non-Premium'), "{$nonPremiumUsers} ({$nonPremiumPercentage}%)")
                ->description(__('Comptes standards limités à 1 produit'))
                ->descriptionIcon('heroicon-m-user-minus')
                ->color('gray'),

            Stat::make(__('Produits publiés'), $publishedProducts)
                ->description("{$draftProducts} " . __('produit(s) en brouillon'))
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('info'),

            Stat::make(__('Vues totales'), number_format($totalViews, 0, ',', ' '))
                ->description(__('Sur l\'ensemble des produits exposés'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
        ];
    }
}
