<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;

class DropdownField extends Component
{
    public $block;
    public $field;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($block = null, $field = null)
    {
        $this->block = (object) $block;
        $this->field = (object) $field;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('module-designer::components.dropdown-field');
    }
}
