<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;

class DetailedField extends Component
{
    public $color;
    public $large;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($color = null, $large = false)
    {
        $this->color = $color;
        $this->large = $large;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('module-designer::components.detailed-field');
    }
}
