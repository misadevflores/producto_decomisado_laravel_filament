<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
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
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Support\Enums\MaxWidth;
use App\Models\User;
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->path('admin')
        ->login()
        ->authGuard('web')

        ->brandName('Panel de Gestión Decomiso')
        ->colors([
            'primary' => Color::Red, // Puedes cambiar 'Amber' por cualquier otro Color::XYZ
                // También puedes definir otros roles de color si lo deseas
                // 'danger' => Color::Red,
                // 'warning' => Color::Orange,
                // 'success' => Color::Green,
                // 'info' => Color::Blue,
        ])
      
        // Descubre todos los resources desde esta carpeta/namespace
        ->discoverResources(
            in: app_path('Filament/Resources'),
            for: 'App\\Filament\\Resources'
        )
        // Descubre páginas desde esta ruta...
        ->discoverPages(
            in: app_path('Filament/Pages'),
            for: 'App\\Filament\\Pages'
        )
        // ...y además agrega manualmente una
        ->pages([
            Pages\Dashboard::class,
        ])
        // Descubre widgets automáticamente desde este path
        ->discoverWidgets(
            in: app_path('Filament/Widgets'),
            for: 'App\\Filament\\Widgets'
        ) 
        // Y agrega manualmente algunos
        ->widgets([
            Widgets\AccountWidget::class,
            // Widgets\FilamentInfoWidget::class,
        ])
        ->plugins([
            \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
        ])
        ->authMiddleware([
            Authenticate::class,
        ])
        ->maxContentWidth(MaxWidth::Full)
        ->sidebarCollapsibleOnDesktop() // <-- activar el sidebar en desktop
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
        ]);
    }
}
