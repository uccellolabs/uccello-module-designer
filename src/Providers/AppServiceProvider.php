<?php

namespace Uccello\ModuleDesigner\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Uccello\ModuleDesigner\Http\Livewire\ModuleDesigner;
use Uccello\ModuleDesigner\View\Components\Block;
use Uccello\ModuleDesigner\View\Components\Column;
use Uccello\ModuleDesigner\View\Components\ColumnTag;
use Uccello\ModuleDesigner\View\Components\DetailedField;
use Uccello\ModuleDesigner\View\Components\DropdownField;
use Uccello\ModuleDesigner\View\Components\FieldConfig;
use Uccello\ModuleDesigner\View\Components\IconSelector;
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

        Blade::components([
            'vertical-step-card' =>  VerticalStepCard::class,
            'vertical-step-card-title' => VerticalStepCardTitle::class,
            'column' => Column::class,
            'column-tag' => ColumnTag::class,
            'field-config' => FieldConfig::class,
            'icon-selector' => IconSelector::class,
            'detailed-field' => DetailedField::class,
            'dropdown-field' => DropdownField::class,
            'block' => Block::class,
        ], 'md');

        Livewire::component('module-designer', ModuleDesigner::class);
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
