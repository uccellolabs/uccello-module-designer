<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Uccello\Core\Models\Module;
use Uccello\ModuleDesigner\Support\Traits\FieldColors;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesignerCore\Models\DesignedModule;

class ActionSelection extends Component
{
    use StepManager, FieldColors;

    public $action = 'create';
    public $canDesignModule = true;

    public $crudModules = [];
    public $designedModules = [];
    public $editedModuleId;
    public $editedDesignedModuleId;

    private $structure;

    protected $listeners = ['stepChanged'];

    public function render()
    {
        return view('module-designer::livewire.action-selection');
    }

    public function changeAction($action)
    {
        $this->canDesignModule = false;

        $this->initActionVariables();

        if ($action === 'create') {
            $this->canDesignModule = true;
        } if ($action === 'edit') {
            $this->loadCrudModules();
        } elseif ($action === 'continue') {
            $this->loadDesignedModules();
        }

        $this->action = $action;
    }

    public function selectModuleToEdit($editedModuleId)
    {
        $this->editedModuleId = $editedModuleId;
        $this->canDesignModule = true;
    }

    public function selectDesignedModuleToEdit($editedDesignedModuleId)
    {
        $this->editedDesignedModuleId = $editedDesignedModuleId;
        $this->canDesignModule = true;
    }

    public function initModuleDesign()
    {
        if ($this->canDesignModule) {
            $this->buildModuleStructure();
            $this->noticeModuleStructureChanged();
            $this->incrementStep();
        }
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

    private function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    private function loadDesignedModules()
    {
        $this->designedModules = DesignedModule::all();
    }

    private function buildModuleStructure()
    {
        if ($this->action === 'create') {
            $this->buildNewStructure();
        } elseif ($this->action === 'edit') {
            $this->buildStructureFromModule();
        } elseif ($this->action === 'continue') {
            $this->retrieveStructureFromDesignedModule();
        }
    }

    private function buildNewStructure()
    {
        $this->structure = [
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
        ];
    }

    private function buildStructureFromModule()
    {
        $module = Module::find($this->editedModuleId);
        $modelClass = $module->model_class;
        $model = new $modelClass;

        $this->structure = [
            'id' => $module->id,
            'label' => uctrans($module->name, $module),
            'name' => $module->name,
            'lastName' => $module->name,
            'icon' => $module->icon,
            'table' => $model->getTable(),
            'lastTable' => $model->getTable(),
            'step' => 0,
            'tabs' => $this->buildTabsStructure($module)
        ];
    }

    private function buildTabsStructure(Module $module)
    {
        $tabs = [];

        foreach ($module->tabs->sortBy('sequence') as $tab) {
            $tabs[] = [
                'uuid' => Str::uuid(),
                'label' => $tab->label,
                'translation' => uctrans($tab->label, $module),
                'icon' => $tab->icon,
                'blocks' => $this->buildBlocksStructure($module, $tab)
            ];
        }

        return $tabs;
    }

    private function buildBlocksStructure(Module $module, $tab)
    {
        $blocks = [];

        foreach ($tab->blocks->sortBy('sequence') as $block) {
            $blocks[] = [
                'uuid' => Str::uuid(),
                'label' => $block->label,
                'translation' => uctrans($block->label, $module),
                'icon' => $block->icon,
                'sequence' => $block->sequence,
                'fields' => $this->buildFieldsStructure($module, $block)
            ];
        }

        return $blocks;
    }

    private function buildFieldsStructure(Module $module, $block)
    {
        $fields = [];

        foreach ($block->fields->sortBy('sequence') as $field) {
            $fields[] = [
                'label' => uctrans('field.'.$field->name, $module),
                'name' => $field->name,
                'color' => $this->colors[2], // FIXME: Set automatic color
                'isRequired' => $field->required,
                'isLarge' => $field->data->large ?? false,
                'isDisplayedInListView' => true,
                'uitype' => uitype($field->uitype_id)->name,
                'displaytype' => displaytype($field->displaytype_id)->name,
                'sequence' => $field->sequence,
                'filterSequence' => $field->sequence, // FIXME: Retrive from filter
                'sortOrder' => null,
                'default' => $field->data->default ?? '',
                'isEditable' => true,
                'options' => []
            ];
        }

        return $fields;
    }

    private function retrieveStructureFromDesignedModule()
    {
        $designedModule = DesignedModule::find($this->editedDesignedModuleId);
        $this->structure = $designedModule->data;
    }

    private function noticeModuleStructureChanged()
    {
        $this->emit('structureChanged', $this->structure);
    }
}
