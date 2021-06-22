<?php

namespace Uccello\ModuleDesigner\Providers;

use BladeUI\Icons\Factory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Uccello\ModuleDesigner\Http\Livewire\ActionSelection;
use Uccello\ModuleDesigner\Http\Livewire\ColumnsCreation;
use Uccello\ModuleDesigner\Http\Livewire\FieldsDisposition;
use Uccello\ModuleDesigner\Http\Livewire\ModuleDesigner;
use Uccello\ModuleDesigner\Http\Livewire\IconSelector;
use Uccello\ModuleDesigner\Http\Livewire\ModuleDescription;
use Uccello\ModuleDesigner\Http\Livewire\RelatedlistsCreation;
use Uccello\ModuleDesigner\View\Components\Block;
use Uccello\ModuleDesigner\View\Components\Column;
use Uccello\ModuleDesigner\View\Components\ColumnTag;
use Uccello\ModuleDesigner\View\Components\DetailedField;
use Uccello\ModuleDesigner\View\Components\DropdownField;
use Uccello\ModuleDesigner\View\Components\FieldConfig;
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
        Livewire::component('md-action-selection', ActionSelection::class);
        Livewire::component('md-module-description', ModuleDescription::class);
        Livewire::component('md-columns-creation', ColumnsCreation::class);
        Livewire::component('md-fields-disposition', FieldsDisposition::class);
        Livewire::component('md-relatedlists-creation', RelatedlistsCreation::class);
        Livewire::component('icon-selector', IconSelector::class);
    }

    public function register()
    {
        // Config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/module-designer.php',
            'module-designer'
        );

        // SVG icons
        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('mdicons', [
                'path' => __DIR__ . '/../../resources/svg',
                'prefix' => 'mdicon',
            ]);
        });
    }
}
