<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait HasStep
{
    public $step = 0;

    public function incrementStep()
    {
        if ($this->step === 0) {
            $this->createOrUpdateTableAndModule();
        }

        $this->step++;
    }

    public function changeStep($step)
    {
        $this->step === $step;
    }

    private function isConfiguringModuleName()
    {
        return $this->step === 0;
    }
}
