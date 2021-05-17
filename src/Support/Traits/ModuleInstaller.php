<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Str;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Tab;

trait ModuleInstaller
{
    use FileCreator;

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

    protected function updateModuleFromStructure()
    {
        $this->module->name = $this->structure['name'];
        $this->module->icon = $this->structure['icon'];
        $this->module->save();
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
        return !empty($this->structure['id']);
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
            'icon' => $this->structure['icon'] ?? null,
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
        $filter->order = $this->getDefaultSortOrder();
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

    private function getDefaultSortOrder()
    {
        $sortOrder = null;
        foreach ($this->fields as $field) {
            $field = (object) $field;
            if ($field->sortOrder) {
                $databaseColumn = $this->getFieldDatabaseColumn($field);
                $sortOrder = [
                    $databaseColumn => $field->sortOrder
                ];
                break;
            }
        }

        return $sortOrder;
    }

    private function getFieldDatabaseColumn($field)
    {
        // Create an uccello field object to be able get default database column name.
        $uccelloField = new Field();
        $uccelloField->module_id = $this->module->id;
        $uccelloField->name = $field->name;
        $uccelloField->uitype_id = uitype($field->uitype)->id;
        $uccelloField->displaytype_id = displaytype($field->displaytype)->id;
        $uccelloField->data = $this->getFormattedFieldData($field);
        $uccelloField->sequence = $field->sequence;

        $uitypeInstance = $this->getUitypeInstance($this->toArray($field));

        return $uitypeInstance->getDefaultDatabaseColumn($uccelloField);
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
        $this->deleteRelatedlistsInRelatedModules();
        $this->deleteTabs();
    }

    private function deleteRelatedlistsInRelatedModules()
    {
        foreach ($this->module->fields() as $field) {
            if ($field->uitype_id === uitype('entity')->id) {
                $relatedModuleName = $field->data->module ?? null;
                if ($relatedModuleName) {
                    $relatedModule = Module::where('name', $relatedModuleName)->first();
                    if ($relatedModule) {
                        $relatedModule->relatedlists()->where('related_field_id', $field->id)->delete();
                    }
                }
            }
        }
    }

    private function deleteTabs()
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

    private function createUccelloField($field)
    {
        $uccelloField = Field::create([
            'module_id' => $this->module->id,
            'block_id' => $field->block_id,
            'uitype_id' => uitype($field->uitype)->id,
            'displaytype_id' => displaytype($field->displaytype)->id,
            'name' => $field->name,
            'data' => $this->getFormattedFieldData($field),
            'sequence' => $field->sequence
        ]);

        if ($field->data['relatedlist'] ?? false) {
            $sourceModule = Module::where('name', $field->data["module"])->first();

            Relatedlist::firstOrCreate([
                'module_id' => $sourceModule->id,
                'related_module_id' => $this->module->id,
                'related_field_id' => $uccelloField->id
            ], [
                'tab_id' => null,
                'label' => $this->structure['label'],
                'type' => 'n-1',
                'method' => 'getDependentList',
                'sequence' => $sourceModule->relatedlists()->count(),
                'data' => [ 'actions' => [ 'add' ] ]
            ]);
        }

        return $uccelloField->id;
    }

    private function getFormattedFieldData($field)
    {
        if ($field->isRequired) {
            $field->data['rules'] = 'required';
        }

        if ($field->isLarge) {
            $field->data['large'] = true;
        }

        if ($field->default) {
            $field->data['default'] = $field->default;
        }

        $bundle = $this->makeBundle($field);
        $uitype = $this->getFieldUitype($this->toArray($field));

        $formattedFieldData = (new ($uitype->class))->getFormattedFieldDataAndTranslationFromOptions($bundle);

        return !empty($formattedFieldData['data']) ? $formattedFieldData['data'] : null;
    }
}
