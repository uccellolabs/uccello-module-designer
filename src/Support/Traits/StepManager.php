<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait StepManager
{
    public $step = 0;

    public function changeStep($step)
    {
        $this->emit('stepChanged', $step);
    }

    public function incrementStep()
    {
        $this->changeStep($this->step + 1);
    }

    public function onStepChanged($step)
    {
        $this->step = $step;
    }

    private function isChoosingAction()
    {
        return $this->step === 0;
    }

    private function isSettingModuleDescription()
    {
        return $this->step === 1;
    }

    private function isSettingModuleAdvancedConfig()
    {
        return $this->step === 2;
    }

    private function isCreatingColumns()
    {
        return $this->step === 3;
    }

    private function isConfiguringFields()
    {
        return $this->step === 4;
    }

    private function isConfiguringRecordLabel()
    {
        return $this->step === 5;
    }
}
