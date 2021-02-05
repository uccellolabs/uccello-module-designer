<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;

class Column extends Component
{
    public $color;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($color = null)
    {
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('module-designer::components.column');
    }
}
