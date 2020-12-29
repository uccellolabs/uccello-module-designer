<?php

namespace Uccello\ModuleDesignerUi\Http\Controllers\ModuleDesigner;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use stdClass;
use Uccello\Core\Facades\Uccello;
use Uccello\Core\Http\Controllers\Core\IndexController as CoreIndexController;
use Uccello\Core\Models\Displaytype;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Uitype;
use Uccello\Core\Models\Widget;
use Uccello\ModuleDesigner\Models\DesignedModule;
use Uccello\ModuleDesigner\Support\ModuleImport;

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

        // Get CRUD modules accessible by user
        $crudModules = $this->getCrudModules();

        // Get pending designed modules
        $designedModules = $this->getDesignedModules();

        return $this->autoView(compact(
            'packages',
            'widgets',
            'uitypes',
            'displaytypes',
            'crudModules',
            'designedModules',
        ));
    }

    /**
     * Returns all available config param for a specific uitype.
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function fieldConfig(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        $uitype = Uitype::where('name', $request->uitype)->first();

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
     * Save current config for designed module
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function save(?Domain $domain, Module $module, Request $request)
    {
        $structure = json_decode($request->structure);

        // Search by id (allows to update module name)
        if (!empty($structure->designed_module_id)) {
            $designedModule = DesignedModule::find($structure->designed_module_id);
            $designedModule->name = $structure->name;
        }

        // Create a new config
        if (empty($designedModule)) {
            $designedModule = DesignedModule::firstOrNew([
                'name' => $structure->name,
            ]);
        }

        $designedModule->data = $structure;
        $designedModule->save();

        return $designedModule;
    }

    public function install(?Domain $domain, Module $module, Request $request)
    {
        $designedModule = DesignedModule::findOrFail($request->id);

        $structure = $designedModule->data;
        $structure->model = 'App\Employee'; // TODO: auto generate

        $import = new ModuleImport();
        $import->install($structure);

        Artisan::call('cache:clear');

        return response()->json(['success' => true]);
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

    protected function getCrudModules()
    {
        $modules = Module::whereNotNull('model_class')->get();

        $crudModules = collect();
        foreach ($modules as $module) {
            if (!Auth::user()->canRetrieve($this->domain, $module)) {
                continue;
            }

            $_module = new stdClass();
            $_module->name = $module->name;
            $_module->label = uctrans($module->name, $module);
            $_module->blocks = collect();

            // Get all blocks
            foreach ($module->blocks->sortBy('sequence') as $block) {
                $_block = new stdClass();
                $_block->label = $block->label;
                $_block->labelTranslated = uctrans($block->label, $module);
                $_block->fields = collect();

                foreach ($block->fields->where('uitype_id', 10)->sortBy('sequence') as $field) {
                    $_field = new stdClass();
                    $_field->name = $field->name;
                    $_field->label = uctrans('field.'.$field->name, $module);
                    $_block->fields[] = $_field;
                }

                if ($_block->fields->count() > 0) {
                    $_module->blocks[] = $_block;
                }
            }

            $crudModules[] = $_module;
        }

        return $crudModules->sortBy('label');
    }

    /**
     * Returns all pending designed modules.
     *
     * @return Collection|null
     */
    protected function getDesignedModules()
    {
        return DesignedModule::orderBy('created_at', 'desc')->get();
    }
}
