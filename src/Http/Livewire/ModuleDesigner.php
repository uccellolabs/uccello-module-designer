<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\ModuleDesigner\Support\Traits\ModuleInstaller;
use Uccello\ModuleDesigner\Support\Traits\TableCreator;
use Uccello\ModuleDesignerCore\Models\DesignedModule;

class ModuleDesigner extends Component
{
    use ModuleInstaller;
    use TableCreator;

    public $column = '';
    public $step = 2;
    public $currentUitype;

    public $designedModule;
    public $name;
    public $label;
    public $icon;
    public $fields;
    public $blocks;
    public $areAvailableFields;

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

    public function createField()
    {
        $this->addField([
            'block_uuid' => null,
            'label' => $this->column,
            'name' => Str::slug($this->column, '_'),
            'last_name' => null,
            'color' => $this->getColor(),
            'isMandatory' => false,
            'isLarge' => false,
            'isDisplayedInListView' => true,
            'uitype' => 'text',
            'displaytype' => 'everywhere',
            'sequence' => $this->fields->count(),
            'options' => []
        ]);

        $this->column = '';
        $this->checkIfThereAreAvailableFields();
    }

    public function createBlock()
    {
        $index = $this->blocks->count() + 1;
        $this->addBlock([
            'uuid' => Str::uuid(),
            'label' => 'block.block'.$index,
            'translation' => 'Block '.$index,
            'icon' => null,
            'sequence' => $this->blocks->count(),
        ]);
    }

    public function createOrUpdateTableAndModule()
    {
        $this->createOrUpdateTable();
        $this->createOrUpdateModule();
    }

    // public function toggleLarge($fieldName)
    // {
    //     $this->fields = $this->fields->map(function ($field) use ($fieldName) {
    //         if ($field['name'] === $fieldName) {
    //             $field['isLarge'] = !$field['isLarge'];
    //         }

    //         return $field;
    //     });
    // }

    // public function toggleMandatory($fieldName)
    // {
    //     $this->fields = $this->fields->map(function ($field) use ($fieldName) {
    //         if ($field['name'] === $fieldName) {
    //             $field['isMandatory'] = !$field['isMandatory'];
    //         }

    //         return $field;
    //     });
    // }

    // public function toggleIsDisplayedInListView($fieldName)
    // {
    //     $this->fields = $this->fields->map(function ($field) use ($fieldName) {
    //         if ($field['name'] === $fieldName) {
    //             $field['isDisplayedInListView'] = !$field['isDisplayedInListView'];
    //         }

    //         return $field;
    //     });
    // }

    public function updateColumnsOrder($sortedFields)
    {
        foreach ($sortedFields as $sortedField) {
            $this->fields = $this->fields->map(function ($field) use ($sortedField) {
                if ($field['name'] === $sortedField['value']) {
                    $field['sequence'] = $sortedField['order'] - 1;
                }

                return $field;
            });
        }
    }

    public function removeFieldFromBlock($fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName) {
            if ($field['name'] === $fieldName) {
                $field['block_uuid'] = null;
            }

            return $field;
        });

        $this->checkIfThereAreAvailableFields();
    }

    public function addFieldToBlock($blockUuid, $fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($blockUuid, $fieldName) {
            if ($field['name'] === $fieldName) {
                $field['block_uuid'] = $blockUuid;
            }

            return $field;
        });

        $this->checkIfThereAreAvailableFields();
    }

    public function incrementStep()
    {
        if ($this->step === 0) {
            $this->createOrUpdateTableAndModule();
        }

        $this->step++;
    }

    public function changeUitype($fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName) {
            if ($field['name'] === $fieldName) {
                $field['options'] = $this->getUitypeFieldOptions($field);
                $field['data'] = null;
            }

            return $field;
        });
    }

    private function getUitypeFieldOptions($field)
    {
        $uitype = uitype($field['uitype']);
        $options = (new ($uitype->class))->getFieldOptions();

        foreach ($options as $i => $option) {
            foreach ($option as $j => $value) {
                if (is_callable($value)) {
                    $options[$i][$j] = call_user_func($value);
                }
            }
        }

        return $options;
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

    private function getColor()
    {
        return $this->colors[count($this->fields) % count($this->colors)];
    }

    private function addField($field)
    {
        if (!empty($field['last_name']) && $field['name'] !== $field['last_name']) {
            $this->updateColumnInExistingTable($field);
        } else {
            $this->createColumnInExistingTable($field);
        }

        $field['last_name'] = $field['name'];

        $this->fields[] = $field;
    }

    private function addBlock($params)
    {
        $this->blocks[] = $params;
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

    private function checkIfThereAreAvailableFields()
    {
        $this->areAvailableFields = false;
        foreach ($this->fields as $field) {
            if (empty($field->block_uuid)) {
                $this->areAvailableFields = true;
                break;
            }
        }
    }

    public function render()
    {
        return view('module-designer::livewire.module-designer');
    }
}
