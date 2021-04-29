<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait StructureManager
{
    public $structure;

    public function changeStructure($structure)
    {
        $this->emit('structureChanged', $structure);
    }

    public function structureChanged($structure)
    {
        $this->structure = $structure;
    }
}
