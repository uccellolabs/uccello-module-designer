<?php

namespace Uccello\ModuleDesigner\View\Components;

use Illuminate\View\Component;
use Uccello\Core\Models\Uitype;

class FieldConfig extends Component
{
    public $field;
    public $index;
    public $uitypes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($field = null, $index = -1)
    {
        $this->field = (object) $field;
        $this->index = $index;
        $this->uitypes = $this->getUitypes();
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

    private function getUitypes()
    {
        $uitypes = collect();

        foreach (Uitype::all() as $uitype) {
            if (!class_exists($uitype->class)) {
                continue;
            }

            $package = $uitype->package;
            $uitype->label = trans("$package::uitype.uitype.$uitype->name");

            $uitypes[] = $uitype;
        };

        return $uitypes->sortBy('label');
    }
}
