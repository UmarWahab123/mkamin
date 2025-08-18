<?php

namespace Filawidget;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filawidget\Pages\Appearance;
use Filawidget\Resources\PageResource;
use Filawidget\Resources\WidgetAreaResource;
use Filawidget\Resources\WidgetAreaResource\Widgets\WidgetAreaStatsOverview;
use Filawidget\Resources\WidgetFieldResource;
use Filawidget\Resources\WidgetResource;
use Filawidget\Resources\WidgetResource\Widgets\WidgetStatsOverview;
use Filawidget\Resources\WidgetTypeResource;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class FilaWidgetPlugin implements Plugin
{
    public function getId(): string
    {
        return 'fila-widget';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                PageResource::class,
                WidgetResource::class,
                WidgetAreaResource::class,
                WidgetFieldResource::class,
                WidgetTypeResource::class,
            ])
            ->pages([
                Appearance::class,
            ])
            ->widgets([
                WidgetStatsOverview::class,
                WidgetAreaStatsOverview::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        if (config('filawidget.show_home_link')) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn (): View => view('filawidget::components.home'),
            );
        }

        if (Route::currentRouteName() === 'filament.admin.pages.appearance') {
            FilamentView::registerRenderHook(
                PanelsRenderHook::CONTENT_START,
                fn (): View => view('filawidget::components.filter'),
            );
        }

        if (config('filawidget.show_quick_appearance')) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::TOPBAR_START,
                fn (): View => view('filawidget::components.quick'),
            );
        }
    }
}
