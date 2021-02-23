<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Str;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;

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

    protected function updateBlocksAndFields()
    {
        $this->retrieveModuleByIdDefinedInStructure();
        $this->deleteModuleFieldsStructure();
        $this->createModuleFieldsStructure();
    }

    private function createOrRetrieveModuleFromStructure()
    {
        if ($this->isModuleIdDefinedInStructure()) {
            $this->retrieveModuleByIdDefinedInStructure();
        } else {
            $this->createModuleFromStructureAndActivateOnDomains();
        }
    }

    private function isModuleIdDefinedInStructure()
    {
        return $this->structure['id'];
    }

    private function retrieveModuleByIdDefinedInStructure()
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
        foreach ($this->fields->sortBy('filterSequence') as $field) {
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

    /**
     * Deletes all module's tabs.
     * When a tab is delete, all blocks and fields are deleted too thanks to onDelete Cascade.
     *
     * @return void
     */
    private function deleteModuleFieldsStructure()
    {
        $this->module->tabs()->delete();
    }

    private function createModuleFieldsStructure()
    {
        $structure = (object) $this->buildDesignedModuleStructure();

        foreach ($structure->tabs as $tab) {
            $tab = (object) $tab;
            $tabId = $this->createUccelloTab($tab);

            foreach ($tab->blocks as $block) {
                $block = (object) $block;
                $block->tab_id = $tabId;
                $blockId = $this->createUccelloBlock($block);

                foreach ($block->fields as $field) {
                    $field = (object) $field;
                    $field->block_id = $blockId;

                    $this->createUccelloField($field);
                }
            }
        }
    }

    private function createUccelloTab($data)
    {
        $tab = Tab::create([
            'module_id' => $this->module->id,
            'label' => $data->label,
            'icon' => $data->icon,
            'sequence' => 0
        ]);

        return $tab->id;
    }

    private function createUccelloBlock($data)
    {
        $block = Block::create([
            'module_id' => $this->module->id,
            'tab_id' => $data->tab_id,
            'label' => $data->label,
            'icon' => $data->icon,
            'sequence' => $data->sequence
        ]);

        return $block->id;
    }

    private function createUccelloField($data)
    {
        $fieldData = !empty($data->data) ? (object) $data->data : new \stdClass;

        if ($data->isRequired) {
            $fieldData->rules = 'required';
        }

        if ($data->isLarge) {
            $fieldData->large = true;
        }

        if (count(get_object_vars($fieldData)) === 0) {
            $fieldData = null;
        }

        $field = Field::create([
            'module_id' => $this->module->id,
            'block_id' => $data->block_id,
            'uitype_id' => uitype($data->uitype)->id,
            'displaytype_id' => displaytype($data->displaytype)->id,
            'name' => $data->name,
            'data' => $fieldData,
            'sequence' => $data->sequence
        ]);



        return $field->id;
    }
}
