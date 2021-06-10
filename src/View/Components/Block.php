<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;

class Block extends Component
{
    public $block;
    public $fields;
    public $index;
    public $areAvailableFields;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($block, $index, $areAvailableFields = false)
    {
        $this->block = (object) $block;
        $this->fields = !empty($block['fields']) ? collect($block['fields']) : collect();
        $this->index = $index;
        $this->areAvailableFields = $areAvailableFields;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('module-designer::components.block');
    }
}
