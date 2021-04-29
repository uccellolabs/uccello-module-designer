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

    public function stepChanged($step)
    {
        $this->step = $step;
    }
}
