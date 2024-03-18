<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\BssInfo;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\VersionCheck;
use App\Http\Middleware\AdminGuard;
use App\Http\Middleware\AdminPanelAuthentication;
use App\Http\Middleware\BssTrackerAuthentication;
use App\Http\Middleware\EnsureApplicationIsSetup;
use App\Http\Middleware\EnsureUserIsAdministrator;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Administration')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->userMenuItems([
                'logout' => MenuItem::make()->label('Log out'),
            ])
            ->discoverResources(in: app_path('Admin/Filament/Resources'), for: 'App\\Admin\\Filament\\Resources')
            ->discoverPages(in: app_path('Admin/Filament/Pages'), for: 'App\\Admin\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->widgets([
                VersionCheck::class,
                Widgets\AccountWidget::class,
                BssInfo::class,
            ])
            //->viteTheme('resources/css/filament/admin/theme.css')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                EnsureApplicationIsSetup::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                EnsureUserIsAdministrator::class,
            ])
            ->authMiddleware([
                AdminPanelAuthentication::class,
            ]);
    }
}
