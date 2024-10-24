<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\PedidoProveedorChart;
use App\Filament\Widgets\PedidosChart;
use App\Filament\Widgets\TestChartWidget;
use App\Filament\Widgets\TestWidget;
use App\Filament\Widgets\VentasChart;
use App\Filament\Widgets\VentasClienteChart;
use App\Filament\Widgets\VentasTiendaChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                TestWidget::class,
                PedidosChart::class,
                VentasChart::class,
                TestChartWidget::class,
                PedidoProveedorChart::class,
                VentasTiendaChart::class,
                VentasClienteChart::class
            ])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                TwoFactorAuthenticationPlugin::make()
                    ->addTwoFactorMenuItem(), // Add 2FA settings to user menu items
                ActivitylogPlugin::make(),
                FilamentAuthenticationLogPlugin::make(),
            ]);
    }
}
