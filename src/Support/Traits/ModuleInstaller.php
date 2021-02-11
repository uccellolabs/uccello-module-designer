<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

trait ModuleInstaller
{
    public $id;

    public function createOrUpdateModule()
    {
        if ($this->id) {
            $module = Module::find($this->id);
        } else {
            $module = new Module();
        }

        $module->name = $this->name;
        $module->icon = null; //TODO:complete
        $module->model_class = 'App\\'.Str::studly($this->name);
        $module->data = null;
        $module->save();

        $this->id = $module->id;

        return $module;
    }

    protected function activateModuleOnDomains($module)
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $domain->modules()->attach($module);
        }
    }
}
