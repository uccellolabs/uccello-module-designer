<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait StructureManager
{
    public $structure;

    public function emitStructureChangedEvent($structure)
    {
        $this->emit('structureChanged', $structure);
    }

    public function onStructureChanged($structure)
    {
        $this->structure = $structure;
    }
}
