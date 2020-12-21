<?php

namespace Uccello\ModuleDesignerUi\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * App Service Provider
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        // Views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'module-designer-ui');

        // Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'module-designer-ui');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/uccello/module-designer-ui'),
        ], 'module-designer-ui-assets');
    }

    public function register()
    {
        //
    }
}
