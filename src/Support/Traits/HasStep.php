<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait HasStep
{
    public $step = 0;

    public function incrementStep()
    {
        $this->createOrUpdateTableAndModule();

        if ($this->isConfiguringModuleName()) {
            //
        } elseif ($this->isCreatingColumns()) {
            $this->createOrUpdateFilter();
        } elseif ($this->isConfiguringFields()) {
            $this->updateBlocksAndFields();
        }

        $this->step++;
    }

    public function changeStep($step)
    {
        $this->step = $step;
    }

    private function isConfiguringModuleName()
    {
        return $this->step === 0;
    }

    private function isCreatingColumns()
    {
        return $this->step === 1;
    }

    private function isConfiguringFields()
    {
        return $this->step === 2 || $this->step === 3;
    }
}
