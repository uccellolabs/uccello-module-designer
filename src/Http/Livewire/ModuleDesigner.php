<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\ModuleDesigner\Support\Traits\HasField;
use Uccello\ModuleDesigner\Support\Traits\HasStep;
use Uccello\ModuleDesigner\Support\Traits\HasUitype;
use Uccello\ModuleDesigner\Support\Traits\ModuleInstaller;
use Uccello\ModuleDesigner\Support\Traits\TableCreator;
use Uccello\ModuleDesignerCore\Models\DesignedModule;

class ModuleDesigner extends Component
{
    use HasField;
    use HasStep;
    use HasUitype;
    use ModuleInstaller;
    use TableCreator;

    public $column = '';

    public $currentUitype;

    public $designedModule;
    public $name;
    public $label;
    public $icon;

    public function __construct()
    {
        $this->fields = collect();
        $this->blocks = collect();

        $this->loadLastDesignedModule();
    }

    // public function update()
    // {
    //     $this->areAvailableFields = true;
    //     // foreach ($this->fields as $field) {
    //     //     if (empty($field['block_uuid'])) {
    //     //         $this->areAvailableFields = true;
    //     //         break;
    //     //     }
    //     // }
    // }

    public function dehydrate()
    {
        $this->saveDesignedModule();
    }

    public function updatedLabel()
    {
        $this->name = Str::slug($this->label);
    }

    public function createOrUpdateTableAndModule()
    {
        $this->createOrUpdateTable();
        $this->createOrUpdateModule();
    }

    private function loadLastDesignedModule()
    {
        $designedModule = DesignedModule::orderBy('created_at', 'desc')->first();

        if (!$designedModule) {
            $generalBlock = [
                'uuid' => Str::uuid(),
                'label' => 'block.general',
                'translation' => 'General',
                'icon' => 'info',
                'sequence' => 0,
            ];

            $systemBlock = [
                'uuid' => Str::uuid(),
                'label' => 'block.system',
                'translation' => 'Settings',
                'icon' => 'settings',
                'sequence' => 1,
            ];


            $designedModule = DesignedModule::create([
                'name' => Str::uuid(),
                'data' => [
                    'id' => '',
                    'label' => '',
                    'name' => '',
                    'icon' => '',
                    'table' => '',
                    'fields' => [
                        [
                            'block_uuid' => $systemBlock['uuid'],
                            'label' => 'Assigned to',
                            'name' => 'assigned_to',
                            'color' => $this->colors[2],
                            'isMandatory' => true,
                            'isLarge' => false,
                            'isDisplayedInListView' => true,
                            'uitype' => 'assigned_user',
                            'displaytype' => 'everywhere',
                            'sequence' => 0,
                            'options' => []
                        ],
                        [
                            'block_uuid' => $systemBlock['uuid'],
                            'label' => 'Domain',
                            'name' => 'domain',
                            'color' => $this->colors[3],
                            'isMandatory' => false,
                            'isLarge' => false,
                            'isDisplayedInListView' => false,
                            'uitype' => 'entity',
                            'displaytype' => 'detail',
                            'data' => ['module' => 'domain'],
                            'sequence' => 1,
                            'options' => []
                        ],
                        [
                            'block_uuid' => $systemBlock['uuid'],
                            'label' => 'Created at',
                            'name' => 'created_at',
                            'color' => $this->colors[0],
                            'isMandatory' => false,
                            'isLarge' => false,
                            'isDisplayedInListView' => true,
                            'uitype' => 'datetime',
                            'displaytype' => 'detail',
                            'sequence' => 2,
                            'options' => []
                        ],
                        [
                            'block_uuid' => $systemBlock['uuid'],
                            'label' => 'Updated at',
                            'name' => 'updated_at',
                            'color' => $this->colors[1],
                            'isMandatory' => false,
                            'isLarge' => false,
                            'isDisplayedInListView' => true,
                            'uitype' => 'datetime',
                            'displaytype' => 'detail',
                            'sequence' => 3,
                            'options' => []
                        ]
                    ],
                    'blocks' => [
                        $generalBlock,
                        $systemBlock,
                    ]
                ]
            ]);
        }

        $this->designedModule = $designedModule;
        $this->id = $designedModule->data->id;
        $this->name = $designedModule->data->name;
        $this->lastName = $designedModule->data->name;
        $this->tableName = $designedModule->data->table;
        $this->lastTableName = $designedModule->data->table;
        $this->label = $designedModule->data->label;
        $this->icon = $designedModule->data->icon;
        $this->fields = collect($designedModule->data->fields);
        $this->blocks = collect($designedModule->data->blocks);
        $this->checkIfThereAreAvailableFields();
    }

    private function saveDesignedModule()
    {
        $this->designedModule->data = [
            'id' => $this->id,
            'label' => $this->label,
            'name' => $this->name,
            'icon' => $this->icon,
            'table' => $this->tableName,
            'fields' => $this->fields,
            'blocks' => $this->blocks,
        ];
        $this->designedModule->save();
    }

    public function render()
    {
        return view('module-designer::livewire.module-designer');
    }
}
