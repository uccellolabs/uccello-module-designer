<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\Core\Models\Module;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;

class ModuleDescription extends Component
{
    use StepManager, StructureManager;

    public $label;
    public $label_singular;
    public $category;
    public $name;
    public $isModuleNameAvailable = true;

    protected $listeners = ['stepChanged', 'structureChanged', 'iconSelected'];

    protected $rules = [
        'label' => 'required',
        'label_singular' => 'required',
        'category' => 'required',
        'name' => 'required|unique:uccello_modules,name,%id%',
    ];

    public function updatedLabel()
    {
        $this->label_singular = Str::singular($this->label);
        $this->name = Str::slug(Str::singular($this->label));
    }

    public function updatedName()
    {
        $this->name = Str::slug($this->name);
    }

    public function render()
    {
        return view('module-designer::livewire.module-description');
    }

    // public function checkModuleNameAvailability()
    // {
    //     $moduleName = $this->structure['name'];

    //     if ($moduleName) {
    //         $module = Module::where('name', $moduleName)->first();

    //         if ($module && $module->getKey() != $this->structure['id']) { // Is different module (edition)
    //             $this->isModuleNameAvailable = false;
    //         } else {
    //             $this->isModuleNameAvailable = true;
    //         }
    //     } else {
    //         $this->isModuleNameAvailable = true;
    //     }
    // }

    public function iconSelected($icon, $target)
    {
        $this->structure['icon'] = $icon;
    }

    public function changeModuleVisibility($visibility)
    {
        if ($visibility === 'private') {
            $this->structure['private'] = true;
        } else {
            unset($this->structure['private']);
        }
    }

    public function updateModuleStructure()
    {
        $this->validate();

        $this->updateStructure();

        $this->changeStructure($this->structure);

        $this->incrementStep();
    }

    public function structureChanged($structure)
    {
        $this->structure = $structure;

        $this->label = $this->structure['label'] ?? '';
        $this->label_singular = $this->structure['label_singular'] ?? '';
        $this->category = $this->structure['category'] ?? '';
        $this->name = $this->structure['name'] ?? '';
    }

    private function updateStructure()
    {
        $this->structure['label'] = $this->label;
        $this->structure['label_singular'] = $this->label_singular;
        $this->structure['category'] = $this->category;
        $this->structure['name'] = $this->name;
    }
}
