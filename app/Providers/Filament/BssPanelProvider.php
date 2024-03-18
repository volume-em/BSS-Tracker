<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\BssInfo;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\VersionCheck;
use App\Http\Middleware\BssTrackerAuthentication;
use App\Http\Middleware\EnsureApplicationIsSetup;
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
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class BssPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('bss')
            ->path('')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->userMenuItems([
                'logout' => MenuItem::make()->label('Log out'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ]);

        $middleware = [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            EnsureApplicationIsSetup::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];

        $widgets = [
            VersionCheck::class,
            BssInfo::class,
            StatsOverview::class,
        ];

        try {
            if (\App\Models\Setting::where('setting', '=', 'require_authentication')->where('value', '=', '1')->count() > 0) {
                $panel->login();

                $middleware[] = AuthenticateSession::class;
                $widgets[] = Widgets\AccountWidget::class;

                $panel->authMiddleware([
                    BssTrackerAuthentication::class,
                ]);
            }
        } catch (QueryException | \ErrorException $e) {}

        $panel
            ->middleware($middleware)
            ->widgets($widgets);

        return $panel;
    }
}
