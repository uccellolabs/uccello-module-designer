<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;

class FieldConfig extends Component
{
    public $field;
    public $index;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($field = null, $index = -1)
    {
        $this->field = (object) $field;
        $this->index = $index;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('module-designer::components.field-config');
    }
}
