<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\ModuleDesignerCore\Models\DesignedModule;

class ModuleDesigner extends Component
{
    public $column = '';

    public $designedModule;
    public $name;
    public $label;
    public $icon;
    public $fields = [];

    private $colors = [
        'bg-red-200',
        'bg-blue-200',
        'bg-green-200',
        'bg-purple-200',
        'bg-yellow-200',
        'bg-indigo-200',
        'bg-red-400',
        'bg-blue-400',
        'bg-green-400',
        'bg-purple-400',
        'bg-yellow-400',
        'bg-indigo-400',
    ];

    public function __construct()
    {
        $this->loadLastDesignedModule();
    }

    public function dehydrate()
    {
        $this->saveDesignedModule();
    }

    public function updatedLabel()
    {
        $this->name = Str::slug($this->label);
        // $this->saveDesignedModule();
    }

    public function createField()
    {
        $this->addField([
            'label' => $this->column,
            'name' => Str::slug($this->column, '_'),
            'color' => $this->getColor(),
            'isMandatory' => false,
            'isLarge' => false,
        ]);

        $this->column = '';
    }

    public function toggleLarge($index)
    {
        $this->fields[$index]['isLarge'] = !$this->fields[$index]['isLarge'];
    }

    public function toggleMandatory($index)
    {
        $this->fields[$index]['isMandatory'] = !$this->fields[$index]['isMandatory'];
    }

    private function loadLastDesignedModule()
    {
        $designedModule = DesignedModule::orderBy('created_at', 'desc')->first();

        if (!$designedModule) {
            $designedModule = DesignedModule::create([
                'name' => Str::uuid(),
                'data' => [
                    'label' => '',
                    'name' => '',
                    'icon' => '',
                    'fields' => []
                ]
            ]);
        }

        $this->designedModule = $designedModule;
        $this->name = $designedModule->data->name;
        $this->label = $designedModule->data->label;
        $this->icon = $designedModule->data->icon;
        $this->fields = $designedModule->data->fields;
    }

    private function getColor()
    {
        $fields = $this->fields;
        return $this->colors[count($fields) % count($this->colors)];
    }

    private function addField($params)
    {
        $this->fields[] = $params;
    }

    private function saveDesignedModule()
    {
        $this->designedModule->data = [
            'label' => $this->label,
            'name' => $this->name,
            'icon' => $this->icon,
            'fields' => $this->fields
        ];
        $this->designedModule->save();
    }

    public function render()
    {
        return view('module-designer::livewire.module-designer');
    }
}
