<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\ModuleDesigner\Support\Traits\FileCreator;
use Uccello\ModuleDesigner\Support\Traits\HasField;
use Uccello\ModuleDesigner\Support\Traits\HasStep;
use Uccello\ModuleDesigner\Support\Traits\HasUitype;
use Uccello\ModuleDesigner\Support\Traits\ModuleInstaller;
use Uccello\ModuleDesigner\Support\Traits\StructureAdapter;
use Uccello\ModuleDesigner\Support\Traits\TableCreator;
use Uccello\ModuleDesignerCore\Models\DesignedModule;

class ModuleDesigner extends Component
{
    use HasField;
    use HasStep;
    use HasUitype;
    use StructureAdapter;
    use ModuleInstaller;
    use TableCreator;
    use FileCreator;

    public $column = '';
    public $moduleLabel = '';

    public $currentUitype;

    public $structure;

    private $designedModule;

    public function __construct()
    {
        $this->structure = [];

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

    public function updatedModuleLabel()
    {
        $this->structure['label'] = $this->moduleLabel;
        $this->structure['name'] = Str::slug(Str::singular($this->moduleLabel));
    }

    public function createOrUpdateTableAndModule()
    {
        $this->createOrUpdateTable();
        $this->createOrUpdateModule();
    }

    public function render()
    {
        return view('module-designer::livewire.module-designer');
    }

    private function loadLastDesignedModule()
    {
        $designedModule = DesignedModule::orderBy('created_at', 'desc')->first();

        if (!$designedModule) {
            $designedModule = DesignedModule::create([
                'name' => Str::uuid(),
                'data' => [
                    'id' => null,
                    'label' => '',
                    'name' => '',
                    'lastName' => '',
                    'icon' => null,
                    'table' => '',
                    'lastTable' => '',
                    'step' => 0,
                    'tabs' => [
                        [
                            'uuid' => Str::uuid(),
                            'label' => 'tab.main',
                            'translation' => trans('module-designer::ui.block.config_detail.tab_main'),
                            'icon' => '',
                            'blocks' => [
                                [
                                    'uuid' => Str::uuid(),
                                    'label' => 'block.general',
                                    'translation' => trans('module-designer::ui.block.config_detail.block_general'),
                                    'icon' => 'info',
                                    'sequence' => 0,
                                    'fields' => []
                                ],
                                [
                                    'uuid' => Str::uuid(),
                                    'label' => 'block.system',
                                    'translation' => trans('module-designer::ui.block.config_detail.block_system'),
                                    'icon' => 'settings',
                                    'sequence' => 1,
                                    'fields' => [
                                        [
                                            // 'block_uuid' => $systemBlock['uuid'],
                                            'label' => trans('module-designer::ui.field.assigned_to'),
                                            'name' => 'assigned_to',
                                            'color' => $this->colors[2],
                                            'isRequired' => true,
                                            'isLarge' => false,
                                            'isDisplayedInListView' => true,
                                            'uitype' => 'assigned_user',
                                            'displaytype' => 'everywhere',
                                            'sequence' => 0,
                                            'filterSequence' => 0,
                                            'sortOrder' => null,
                                            'options' => []
                                        ],
                                        [
                                            // 'block_uuid' => $systemBlock['uuid'],
                                            'label' => trans('module-designer::ui.field.domain'),
                                            'name' => 'domain',
                                            'color' => $this->colors[3],
                                            'isRequired' => false,
                                            'isLarge' => false,
                                            'isDisplayedInListView' => false,
                                            'uitype' => 'entity',
                                            'displaytype' => 'detail',
                                            'data' => ['module' => 'domain'],
                                            'sequence' => 1,
                                            'filterSequence' => 1,
                                            'sortOrder' => null,
                                            'options' => []
                                        ],
                                        [
                                            // 'block_uuid' => $systemBlock['uuid'],
                                            'label' => trans('module-designer::ui.field.created_at'),
                                            'name' => 'created_at',
                                            'color' => $this->colors[0],
                                            'isRequired' => false,
                                            'isLarge' => false,
                                            'isDisplayedInListView' => false,
                                            'uitype' => 'datetime',
                                            'displaytype' => 'detail',
                                            'sequence' => 2,
                                            'filterSequence' => 2,
                                            'sortOrder' => 'desc',
                                            'options' => []
                                        ],
                                        [
                                            // 'block_uuid' => $systemBlock['uuid'],
                                            'label' => trans('module-designer::ui.field.updated_at'),
                                            'name' => 'updated_at',
                                            'color' => $this->colors[1],
                                            'isRequired' => false,
                                            'isLarge' => false,
                                            'isDisplayedInListView' => false,
                                            'uitype' => 'datetime',
                                            'displaytype' => 'detail',
                                            'sequence' => 3,
                                            'filterSequence' => 3,
                                            'sortOrder' => null,
                                            'options' => []
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $this->designedModule = $designedModule;

        $this->structure = $this->toArray($designedModule->data);
        $this->moduleLabel = $this->structure['label'];

        $this->buildOptimizedStructure();

        $this->checkIfThereAreAvailableFields();
    }

    private function saveDesignedModule()
    {
        $this->designedModule->data = $this->buildDesignedModuleStructure();
        $this->designedModule->save();
    }
}
