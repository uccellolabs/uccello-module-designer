<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

trait ModuleInstaller
{
    public function createOrUpdateModule()
    {
        if ($this->structure['id']) {
            $module = Module::find($this->structure['id']);
        } else {
            $module = Module::firstOrNew([
                'name' => $this->structure['name']
            ]);
        }

        $module->name = $this->structure['name'];
        $module->icon = null; //TODO:complete
        $module->model_class = 'App\\'.Str::studly($this->structure['name']);
        $module->data = null;
        $module->save();

        $this->structure['id'] = $module->id;

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
