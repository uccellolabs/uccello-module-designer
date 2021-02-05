<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;

class VerticalStepCardTitle extends Component
{

    public $title;
    public $close;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $close = false)
    {
        $this->title = $title;
        $this->close = $close;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('module-designer::components.vertical-step-card-title');
    }
}
