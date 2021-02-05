<?php

namespace Uccello\ModuleDesigner\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Uccello\ModuleDesigner\View\Components\Column;
use Uccello\ModuleDesigner\View\Components\ColumnTag;
use Uccello\ModuleDesigner\View\Components\VerticalStepCard;
use Uccello\ModuleDesigner\View\Components\VerticalStepCardTitle;

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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'module-designer');

        // Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'module-designer');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/uccello/module-designer'),
        ], 'module-designer-assets');

        // Publish config
        $this->publishes([
            __DIR__.'/../../config/module-designer.php' => config_path('module-designer.php')
        ], 'module-designer-config');

        // Blade::componentNamespace('Uccello\\ModuleDesigner\\Views\\Components', 'md');
        Blade::component('vertical-step-card', VerticalStepCard::class, 'md');
        Blade::component('vertical-step-card-title', VerticalStepCardTitle::class, 'md');
        Blade::component('column', Column::class, 'md');
        Blade::component('column-tag', ColumnTag::class, 'md');
    }

    public function register()
    {
        // Config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/module-designer.php',
            'module-designer'
        );
    }
}
