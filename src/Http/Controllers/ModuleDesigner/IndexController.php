<?php

namespace Uccello\ModuleDesignerUi\Http\Controllers\ModuleDesigner;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Uccello\Core\Facades\Uccello;
use Uccello\Core\Http\Controllers\Core\IndexController as CoreIndexController;
use Uccello\Core\Models\Displaytype;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Uitype;
use Uccello\Core\Models\Widget;

class IndexController extends CoreIndexController
{
    /**
     * Process and display asked page
     * @param Domain|null $domain
     * @param Module $module
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get local packages
        $packages = $this->getLocalPackages();

        // Get available summary widgets
        $widgets = $this->getSummaryWidgets();

        // Get all uitypes
        $uitypes = $this->getUitypes();

        // Get all displaytypes
        $displaytypes = $this->getDisplaytypes();

        return $this->autoView(compact(
            'packages',
            'widgets',
            'uitypes',
            'displaytypes',
        ));
    }

    public function fieldConfig(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        $uitype = Uitype::findOrFail($request->uitype);

        // If a special template exists, use it. Else use the generic template
        $uitypeViewName = sprintf('uitypes.module-designer.%s', $uitype->name);
        $uitypeFallbackView = 'module-designer-ui::modules.default.uitypes.module-designer.'.$uitype->name;
        $uitypeViewToInclude = Uccello::view($module->package, $module, $uitypeViewName, $uitypeFallbackView);

        // If view does not exist, returns an empty string
        if (!view()->exists($uitypeViewToInclude)) {
            return '';
        }

        return view()->make($uitypeViewToInclude, compact('domain', 'module', 'uitype'))->render();
    }

    /**
     * Scans packages directory and returns the packages list with the following format: vendor/package
     *
     * @return array
     */
    protected function getLocalPackages()
    {
        $packages = [];

        // Get packages list from
        $packagePath = config('module-designer-ui.packages_directory');

        if (is_dir($packagePath)) {
            // First level directories are vendors
            $vendors = File::directories($packagePath);

            foreach ($vendors as $vendor) {
                // Second level directories are packages
                $vendorPackages = File::directories($vendor);

                foreach ($vendorPackages as $vendorPackage) {
                    $packages[] = File::basename($vendor) . '/' . File::basename($vendorPackage);
                }
            }
        }

        // Sort packages by name
        sort($packages);

        return $packages;
    }

    /**
     * Returns all widgets available with type summay
     *
     * @return Collection|null
     */
    protected function getSummaryWidgets()
    {
        return Widget::where('type', 'summary')->get();
    }

    /**
     * Returns all available uitypes
     *
     * @return Collection|null
     */
    protected function getUitypes()
    {
        $uitypes = collect();

        foreach (Uitype::all() as $uitype) {
            $uitype->label = trans('module-designer-ui::module-designer.uitype.'.$uitype->name);
            // TODO: Translation compatible with uitypes from other packages
            $uitypes[] = $uitype;
        };

        return $uitypes->sortBy('label');
    }

    /**
     * Returns all available displaytypes
     *
     * @return Collection|null
     */
    protected function getDisplaytypes()
    {
        return Displaytype::all();
    }
}
