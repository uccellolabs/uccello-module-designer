<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait HasStep
{
    public $step = 2;

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
}
