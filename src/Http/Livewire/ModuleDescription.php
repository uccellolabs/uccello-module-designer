<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\ModuleDesigner\Support\Traits\FileCreator;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;
use Uccello\ModuleDesigner\Support\Traits\TableCreator;

class ModuleDescription extends Component
{
    use StepManager, StructureManager, FileCreator, TableCreator;

    public $label;
    public $label_singular;
    // public $category;
    public $name;
    public $isModuleNameAvailable = true;

    protected $listeners = [
        'stepChanged' => 'onStepChanged',
        'structureChanged' => 'onStructureChanged',
        'iconSelected' => 'onIconSelected'
    ];

    private $module;

    public function updatedLabel()
    {
        $this->label_singular = Str::singular($this->label);

        if ($this->isNewModule()) {
            $this->name = Str::slug(Str::singular($this->label));
        }
    }

    public function updatedName()
    {
        $this->name = Str::slug($this->name);
    }

    public function render()
    {
        return view('module-designer::livewire.module-description');
    }

    public function onIconSelected($icon, $target)
    {
        if ($target === 'module') {
            $this->structure['icon'] = $icon;
        }
    }

    public function changeModuleVisibility($visibility)
    {
        if ($visibility === 'private') {
            $this->structure['private'] = true;
        } else {
            unset($this->structure['private']);
        }
    }

    public function onStructureChanged($structure)
    {
        $this->structure = $structure;

        $this->label = $this->structure['label'] ?? '';
        $this->label_singular = $this->structure['label_singular'] ?? '';
        // $this->category = $this->structure['category'] ?? '';
        $this->name = $this->structure['name'] ?? '';
    }

    public function saveModule()
    {
        $this->validate(
            [
                'label' => 'required',
                'label_singular' => 'required',
                // 'category' => 'required',
                'name' => 'required|unique:uccello_modules,name,'.$this->structure['id'],
            ]
        );

        if ($this->isSettingModuleDescription()) {
            $this->updateStructure();
            $this->createOrUpdateModule();
        } elseif ($this->isSettingModuleAdvancedConfig()) {
            $this->updateModule();
        }

        $this->emitStructureChangedEvent($this->structure);

        $this->incrementStep();
    }

    public function toggleIsForAdmin()
    {
        $this->structure['admin'] = !$this->structure['admin'];
    }

    private function updateStructure()
    {
        $this->structure['label'] = $this->label;
        $this->structure['label_singular'] = $this->label_singular;
        // $this->structure['category'] = $this->category;
        $this->structure['name'] = $this->name;
    }

    private function createOrUpdateModule()
    {
        if ($this->isNewModule()) {
            $this->createModule();
        } else {
            $this->updateModule();
        }
    }

    private function isNewModule()
    {
        return empty($this->structure['id']);
    }

    private function createModule()
    {
        $this->createModuleInDatabase();
        $this->createOrUpdateTable();
        $this->createOrUpdateModelFile();
        $this->createOrUpdateLanguageFile();
    }

    private function createModuleInDatabase()
    {
        $this->module = Module::create([
            'name' => $this->structure['name'],
            'icon' => $this->structure['icon'] ?? null,
            'model_class' => $this->getModelClass(),
            'data' => $this->getModuleData()
        ]);

        $this->structure['id'] = $this->module->getKey();
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

    private function isModulePartsOfLocalApplication()
    {
        return $this->getVendorNamespace() === 'App';
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

        if ($this->isAdminModule()) {
            $data['admin'] = true;
        } else {
            unset($data['admin']);
        }

        if ($this->isPrivateModule()) {
            $data['private'] = true;
        } else {
            unset($data['private']);
        }

        return $data;
    }

    private function isModulePartsOfCustomPackage()
    {
        return !$this->isModulePartsOfLocalApplication();
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

    private function isAdminModule()
    {
        return $this->structure['admin'] ?? false;
    }

    private function isPrivateModule()
    {
        return $this->structure['private'] ?? false;
    }

    private function updateModule()
    {
        $this->updateModuleInDatabase();
        $this->createOrUpdateTable();
        $this->createOrUpdateModelFile();
        // $this->createOrUpdateLanguageFile(); //FIXME: When we activate it, it removes tabs, blocks, fields, relatedlist translations
    }

    private function updateModuleInDatabase()
    {
        $module = Module::find($this->structure['id']);
        $module->name = $this->structure['name'];
        $module->icon = $this->structure['icon'] ?? null;
        $module->model_class = $this->getModelClass();
        $module->data = $this->getModuleData();
        $module->save();

        $this->module = $module;
    }
}
