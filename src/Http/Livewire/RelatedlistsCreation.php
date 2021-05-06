<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Livewire\Component;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;

class RelatedlistsCreation extends Component
{
    use StepManager, StructureManager;

    protected $listeners = ['stepChanged', 'structureChanged'];

    public function render()
    {
        return view('module-designer::livewire.relatedlists-creation');
    }
}
