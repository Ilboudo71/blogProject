<?php

namespace App\Providers\Filament;

use App\Filament\Auth\EditProfile;
use App\Filament\Auth\Login;
use App\Filament\Auth\Register;
use App\Filament\Auth\RequestPasswordReset;
use App\Filament\Auth\ResetPassword;
use App\Filament\User\Pages\Dashboard;
use App\Http\Middleware\SetLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('user')
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset(RequestPasswordReset::class, ResetPassword::class)
            ->profile(EditProfile::class)
            ->brandName('Raaga')
            ->font('Outfit')
            ->favicon(asset('favicon.ico'))
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Teal,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => Blade::render('@vite(\'resources/css/filament/panel.css\')').
                    '<style id="raaga-panel-type-scale">
                        html.fi, .fi-body, .fi-main, .fi-page { font-size: 18px !important; }
                        .fi-sidebar-item-label, .fi-sidebar-group-label, .fi-topbar-item-label { font-size: 1.15rem !important; }
                        .fi-header-heading { font-size: 2rem !important; }
                        .fi-section-header-heading, .fi-fo-field-wrp-label span, .fi-btn-label, .fi-input, .fi-select-input, .fi-ta-text { font-size: 1.15rem !important; }
                        .fi-wi-stats-overview-stat-value { font-size: 2.15rem !important; }
                        .fi-wi-stats-overview-stat-label { font-size: 1.2rem !important; }
                    </style>',
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn (): string => Blade::render('@include(\'filament.sidebar.logout\')'),
            )
            ->userMenuItems([
                MenuItem::make('language_fr')
                    ->label(fn (): string => app()->getLocale() === 'fr' ? '✓ Français' : 'Français')
                    ->icon('heroicon-o-language')
                    ->url(fn (): string => route('locale.switch', ['locale' => 'fr']))
                    ->sort(100),
                MenuItem::make('language_en')
                    ->label(fn (): string => app()->getLocale() === 'en' ? '✓ English' : 'English')
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn (): string => route('locale.switch', ['locale' => 'en']))
                    ->sort(101),
            ])
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\Filament\User\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\Filament\User\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\Filament\User\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
