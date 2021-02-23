<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;

class VerticalStepCard extends Component
{
    public $title;
    public $step;
    public $after;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $step, $after = null)
    {
        $this->title = $title;
        $this->step = $step;
        $this->after = $after;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('module-designer::components.vertical-step-card');
    }
}
