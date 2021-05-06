<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Livewire\Component;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;

class ModuleDescription extends Component
{
    use StepManager, StructureManager;

    protected $listeners = ['stepChanged', 'structureChanged', 'iconSelected'];

    public function render()
    {
        return view('module-designer::livewire.module-description');
    }

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
}
