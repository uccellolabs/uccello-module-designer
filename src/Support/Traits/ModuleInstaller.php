<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

trait ModuleInstaller
{
    protected $module;

    protected function createOrUpdateModule()
    {
        $this->createOrRetrieveModuleFromStructure();

        $this->structure['id'] = $this->module->id;

        $this->createOrUpdateModuleFiles();
    }

    protected function createOrUpdateFilter()
    {
        $this->createOrRetrieveModuleFromStructure();
        $this->createOrRetrieveModuleDefaultFilter();
    }

    private function createOrRetrieveModuleFromStructure()
    {
        if ($this->isModuleIdDefinedInStructure()) {
            $this->retriveModuleByIdDefinedInStructure();
        } else {
            $this->createModuleFromStructure();
        }
    }

    private function isModuleIdDefinedInStructure()
    {
        return $this->structure['id'];
    }

    private function retriveModuleByIdDefinedInStructure()
    {
        $this->module = Module::find($this->structure['id']);
    }

    private function createModuleFromStructureAndActivateOnDomains()
    {
        $this->createModuleFromStructure();

        $this->activateModuleInAllDomains();
    }

    private function createModuleFromStructure()
    {
        $this->module = Module::create([
            'name' => $this->structure['name'],
            'icon' => $this->structure['icon'],
            'model_class' => $this->getModelClass(),
            'data' => $this->getModuleData()
        ]);
    }

    protected function activateModuleInAllDomains()
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $domain->modules()->attach($this->module);
        }
    }

    private function getModelClass()
    {
        $packageNamespace = $this->getPackageNamespace();
        $modelClass = $this->getModelClassFromModuleName();

        return "$packageNamespace\\Models\\$modelClass";
    }

    private function getPackageNamespace()
    {
        if ($this->isModulePartsOfLocalApplication()) {
            $packageNamespace = 'App';
        } else {
            $vendorNamespace = $this->getVendorNamespace();
            $packageNameStudly = $this->getPackageNameStudly();
            $packageNamespace = "$vendorNamespace\\$packageNameStudly";
        }

        return $packageNamespace;
    }

    private function getVendorNamespace()
    {
        return Str::studly(config('module-designer.default_vendor'));
    }

    private function getPackageNameStudly()
    {
        return Str::studly(config('module-designer.default_package'));
    }

    private function getModelClassFromModuleName()
    {
        return Str::studly($this->structure['name']);
    }

    private function getModuleData()
    {
        $data = [];

        if ($this->isModulePartsOfCustomPackage()) {
            $packageName = $this->getPackageName();
            $data['package'] = $packageName;
        }

        return $data;
    }

    private function getPackageName()
    {
        $packageName = null;

        if ($this->isModulePartsOfCustomPackage()) {
            $vendorNamespace = $this->getVendorNamespace();
            $packageNameStudly = $this->getPackageNameStudly();
            $packageName = strtolower("$vendorNamespace/$packageNameStudly");
        }

        return $packageName;
    }

    private function createOrRetrieveModuleDefaultFilter()
    {
        $filter = $this->module->filters()->firstOrNew([
            'name' => 'filter.all',
            'type' => 'list'
        ], [
            'is_default' => true,
            'data' => [
                'readonly' => true
            ]
        ]);

        $filter->columns = $this->getDefaultFilterColumns();
        $filter->save();
    }

    private function getDefaultFilterColumns()
    {
        $columns = [];
        foreach ($this->fields->sortBy('sequence') as $field) {
            $field = (object) $field;
            if ($field->isDisplayedInListView) {
                $columns[] = $field->name;
            }
        }

        return $columns;
    }

    private function isModulePartsOfLocalApplication()
    {
        return $this->getVendorNamespace() === 'App';
    }

    private function isModulePartsOfCustomPackage()
    {
        return !$this->isModulePartsOfLocalApplication();
    }
}
