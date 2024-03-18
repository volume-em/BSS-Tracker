<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\LogoutResponse;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LogoutResponse::class, function (Application $application) {
            return new \App\Http\Controllers\LogoutResponse();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn(): string => new HtmlString('<link rel="icon" type="image/png" href="/icon.png">')
        );

        if (! Filament::isServing()) {
            FilamentColor::register([
                'primary' => Color::Teal,
                'secondary' => '#1d2755',
                'tertiary' => '#aed2ec',
                'accent' => '#ee1b2e',
            ]);
        }
    }
}
