<?php

namespace App\Providers;

use App\Support\Filawidget\FilaWidgetPlugin;
use Illuminate\Support\ServiceProvider;

class FilawidgetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Nothing to register
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load views from resources
        $this->loadViewsFrom(resource_path('filawidget/resources/views'), 'filawidget');

        // Load translations
        $this->loadTranslationsFrom(resource_path('filawidget/resources/lang'), 'filawidget');

        // Load migrations
        $this->loadMigrationsFrom(resource_path('filawidget/database/migrations'));

        // Load config
        $this->mergeConfigFrom(
            resource_path('filawidget/config/filawidget.php'), 'filawidget'
        );

        // Register the plugin directly so it's not reliant on the vendor package
        if ($this->app->resolved('filament')) {
            $filament = $this->app->make('filament');
            $panels = $filament->getPanels();

            foreach ($panels as $panel) {
                $id = $panel->getId();
                if ($id === 'admin') {
                    // This ensures our plugin is recognized by Filament
                    $this->app->tag('filament-plugin', [FilaWidgetPlugin::class]);
                }
            }
        }
    }
}
