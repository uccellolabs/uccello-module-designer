<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\Core\Models\Module;
use Uccello\ModuleDesigner\Support\Traits\FileCreator;
use Uccello\ModuleDesigner\Support\Traits\HasField;
use Uccello\ModuleDesigner\Support\Traits\HasIcon;
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
    use HasIcon;
    use StructureAdapter;
    use ModuleInstaller;
    use TableCreator;
    use FileCreator;

    public $column = '';
    public $moduleLabel = '';
    public $action = null;

    public $crudModules = [];
    public $designedModules = [];

    public $editedModuleId;
    public $editedDesignedModuleId;
    public $canDesignModule = false;

    public $currentUitype;

    public $structure;

    private $designedModule;

    protected $listeners = ['iconSelected', 'structureBuilt'];


    public function __construct()
    {
        $this->structure = [];

        // dd('test', $this->editedDesignedModuleId);
        if ($this->editedDesignedModuleId) {
            $this->designedModule = DesignedModule::find($this->editedDesignedModuleId);
        }
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

    public function changeAction($action)
    {
        $this->initActionVariables();

        $this->canDesignModule = false;

        if ($action === 'create') {
            $this->canDesignModule = true;
        } if ($action === 'edit') {
            $this->loadCrudModules();
        } elseif ($action === 'continue') {
            $this->loadDesignedModules();
        }

        $this->action = $action;
    }

    public function selectModuleToEdit($moduleId)
    {
        $this->editedModuleId = $moduleId;
        $this->canDesignModule = true;
    }

    public function selectDesignedModuleToEdit($designedModuleId)
    {
        $this->editedDesignedModuleId = $designedModuleId;
        $this->canDesignModule = true;
    }

    public function iconSelected($icon, $target)
    {
        if ($target === 'module') {
            $this->structure['icon'] = $icon;
        }
    }

    public function structureBuilt($structure)
    {
        $this->structure['name'] = $structure['name'];
        $this->structure['label'] = $structure['label'];
        $this->structure['table'] = $structure['name'];
        // $this->structure['fields'] = $structure['fields'];
        // $this->structure['blocks'] = $structure['blocks'];

        $this->createOrUpdateTable();

        $this->moduleLabel = $structure['label'];

        foreach ($structure['fields'] as $field) {
            $field['block_uuid'] = $this->getFirstBlockUuid();
            $field['last_name'] = $field['name'];
            $this->addField($field);
        }
    }

    public function render()
    {
        return view('module-designer::livewire.module-designer');
    }

    private function initActionVariables()
    {
        $this->editedModuleId = null;
        $this->editedDesignedModuleId = null;
        $this->crudModules = [];
        $this->designedModules = [];
    }

    private function loadCrudModules()
    {
        $this->crudModules = Module::whereNotNull('model_class')->get()->map(function ($module) {
            $module->label = uctrans($module->name, $module);
            return $this->toArray($module);
        });
    }

    private function loadDesignedModules()
    {
        $this->designedModules = DesignedModule::all();
    }

    private function createDesignedModule()
    {
        $this->moduleLabel = '';

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
                                        'default' => '',
                                        'isEditable' => false,
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
                                        'default' => '',
                                        'isEditable' => false,
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
                                        'default' => '',
                                        'isEditable' => false,
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
                                        'default' => '',
                                        'isEditable' => false,
                                        'options' => []
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->editedDesignedModuleId = $designedModule->id;

        $this->continueDesignedModule();
    }

    private function saveDesignedModule()
    {
        if (!$this->editedDesignedModuleId || $this->isChoosingAction()) {
            return;
        }

        DesignedModule::find($this->editedDesignedModuleId)->update([
            'data' => $this->buildDesignedModuleStructure()
        ]);
    }
}
