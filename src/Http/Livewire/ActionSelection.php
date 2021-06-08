<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Illuminate\Support\Str;
use Uccello\Core\Models\Module;
use Uccello\ModuleDesigner\Support\Traits\FieldColors;
use Uccello\ModuleDesigner\Support\Traits\FileCreator;
use Uccello\ModuleDesigner\Support\Traits\ModuleInstaller;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesignerCore\Models\DesignedModule;

class ActionSelection extends Component
{
    use StepManager, FieldColors, FileCreator, ModuleInstaller;

    public $action = 'create';
    public $canDesignModule = true;

    public $crudModules = [];
    public $designedModules = [];
    public $editedModuleId;
    public $deletedModuleId;

    private $structure;

    protected $listeners = [
        'stepChanged' => 'onStepChanged'
    ];

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
        } elseif ($action === 'delete') {
            $this->loadCrudModules();
        }

        $this->action = $action;
    }

    public function selectModuleToEdit($editedModuleId)
    {
        $this->editedModuleId = $editedModuleId;
        $this->canDesignModule = true;
    }

    public function selectModuleToDelete($deletedModuleId)
    {
        $this->deletedModuleId = $deletedModuleId;
        $this->canDesignModule = true;
    }

    public function initModuleDesign()
    {
        if ($this->canDesignModule) {
            $this->buildModuleStructure();

            if ($this->action !== 'delete') {
                $this->noticeModuleStructureChanged();
                $this->incrementStep();
            }
        }
    }

    private function initActionVariables()
    {
        $this->editedModuleId = null;
        $this->deletedModuleId = null;
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
            $this->buildStructureFromModule($this->editedModuleId);
        } elseif ($this->action === 'delete') {
            $this->buildStructureFromModule($this->deletedModuleId);
            $this->deleteModule();
        }
    }

    private function buildNewStructure()
    {
        $this->structure = [
            'id' => null,
            'isEditing' => false,
            'label' => '',
            'name' => '',
            'lastName' => '',
            'icon' => null,
            'table' => '',
            'lastTable' => '',
            'step' => 0,
            'tabs' => $this->getDefaultTabs()
        ];
    }

    private function getDefaultTabs()
    {
        $tabUuid = Str::uuid();
        $generalBlockUuid = Str::uuid();
        $systemBlockUuid = Str::uuid();

        return [
            [
                'uuid' => $tabUuid,
                'label' => 'tab.main',
                'translation' => trans('module-designer::ui.block.config_detail.tab_main'),
                'icon' => '',
                'blocks' => [
                    [
                        'tab_uuid' => $tabUuid,
                        'uuid' => $generalBlockUuid,
                        'label' => 'block.general',
                        'translation' => trans('module-designer::ui.block.config_detail.block_general'),
                        'icon' => 'info',
                        'sequence' => 0,
                        'fields' => []
                    ],
                    [
                        'tab_uuid' => $tabUuid,
                        'uuid' => $systemBlockUuid,
                        'label' => 'block.system',
                        'translation' => trans('module-designer::ui.block.config_detail.block_system'),
                        'icon' => 'settings',
                        'sequence' => 1,
                        'fields' => [
                            [
                                'block_uuid' => $systemBlockUuid,
                                'label' => trans('module-designer::ui.field.created_at'),
                                'name' => 'created_at',
                                'color' => $this->colors[0],
                                'isRequired' => false,
                                'isLarge' => false,
                                'isDisplayedInListView' => false,
                                'isSystemField' => true,
                                'uitype' => 'datetime',
                                'displaytype' => 'detail',
                                'sequence' => 0,
                                'filterSequence' => 0,
                                'sortOrder' => 'desc',
                                'default' => '',
                                'isEditable' => false,
                                'isFullyEditable' => false,
                                'options' => []
                            ],
                            [
                                'block_uuid' => $systemBlockUuid,
                                'label' => trans('module-designer::ui.field.updated_at'),
                                'name' => 'updated_at',
                                'color' => $this->colors[1],
                                'isRequired' => false,
                                'isLarge' => false,
                                'isDisplayedInListView' => false,
                                'isSystemField' => true,
                                'uitype' => 'datetime',
                                'displaytype' => 'detail',
                                'sequence' => 1,
                                'filterSequence' => 1,
                                'sortOrder' => null,
                                'default' => '',
                                'isEditable' => false,
                                'isFullyEditable' => false,
                                'options' => []
                            ],
                            // [
                            //     'block_uuid' => $systemBlockUuid,
                            //     'label' => trans('module-designer::ui.field.created_by'),
                            //     'name' => 'created_by',
                            //     'color' => $this->colors[2],
                            //     'isRequired' => true,
                            //     'isLarge' => false,
                            //     'isDisplayedInListView' => false,
                            //     'isSystemField' => true,
                            //     'uitype' => 'entity',
                            //     'displaytype' => 'detail',
                            //     'data' => ['module' => 'user'],
                            //     'sequence' => 2,
                            //     'filterSequence' => 2,
                            //     'sortOrder' => null,
                            //     'default' => '',
                            //     'isEditable' => false,
                            //     'isFullyEditable' => false,
                            //     'options' => []
                            // ],
                            [
                                'block_uuid' => $systemBlockUuid,
                                'label' => trans('module-designer::ui.field.workspace'),
                                'name' => 'domain',
                                'color' => $this->colors[3],
                                'isRequired' => false,
                                'isLarge' => false,
                                'isDisplayedInListView' => false,
                                'isSystemField' => true,
                                'uitype' => 'entity',
                                'displaytype' => 'detail',
                                'data' => ['module' => 'domain'],
                                'sequence' => 3,
                                'filterSequence' => 3,
                                'sortOrder' => null,
                                'default' => '',
                                'isEditable' => false,
                                'isFullyEditable' => false,
                                'options' => []
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }

    private function buildStructureFromModule($moduleId)
    {
        $module = Module::find($moduleId);
        $modelClass = $module->model_class;
        $model = new $modelClass;

        $this->structure = [
            'id' => $module->id,
            'isEditing' => true,
            'label' => uctrans($module->name, $module),
            'label_singular' => uctrans($module->name.'_singular', $module),
            'name' => $module->name,
            'lastName' => $module->name,
            'icon' => $module->icon,
            'table' => $model->getTable(),
            'lastTable' => $model->getTable(),
            'admin' => $module->data->admin ?? false,
            'private' => $module->data->private ?? false,
            'step' => 0,
            'tabs' => $this->buildTabsStructure($module)
        ];

        // getModuleRecordLabel() needs existing structure. So we use it after structure generation
        $this->structure['recordLabel'] = $this->getModuleRecordLabel();
    }

    private function buildTabsStructure(Module $module)
    {
        $tabs = [];

        if ($module->tabs->count() > 0) {
            foreach ($module->tabs->sortBy('sequence') as $tab) {
                $tabUuid = Str::uuid();

                $tabs[] = [
                    'uuid' => $tabUuid,
                    'label' => $tab->label,
                    'translation' => uctrans($tab->label, $module),
                    'icon' => $tab->icon,
                    'blocks' => $this->buildBlocksStructure($module, $tab, $tabUuid)
                ];
            }
        } else {
            $tabs = $this->getDefaultTabs();
        }

        return $tabs;
    }

    private function buildBlocksStructure(Module $module, $tab, $tabUuid)
    {
        $blocks = [];

        foreach ($tab->blocks->sortBy('sequence') as $block) {
            $blockUuid = Str::uuid();

            $blocks[] = [
                'tab_uuid' => $tabUuid,
                'uuid' => $blockUuid,
                'label' => $block->label,
                'translation' => uctrans($block->label, $module),
                'icon' => $block->icon,
                'sequence' => $block->sequence,
                'fields' => $this->buildFieldsStructure($module, $block, $blockUuid)
            ];
        }

        return $blocks;
    }

    private function buildFieldsStructure(Module $module, $block, $blockUuid)
    {
        $fields = [];

        $mainFilter = $module->filters()->where('name', 'filter.all')->first();

        foreach ($block->fields->sortBy('sequence') as $field) {
            $field = [
                'block_uuid' => $blockUuid,
                'label' => uctrans('field.'.$field->name, $module),
                'name' => $field->name,
                'color' => $this->getColor(count($fields)),
                'isRequired' => $field->required,
                'isLarge' => $field->data->large ?? false,
                'isDisplayedInListView' => !empty($mainFilter) && in_array($field->name, $mainFilter->columns),
                'isSystemField' => $this->isSystemField($field),
                'uitype' => uitype($field->uitype_id)->name,
                'displaytype' => displaytype($field->displaytype_id)->name,
                'sequence' => $field->sequence,
                'filterSequence' => $field->sequence, // FIXME: Retrieve from filter
                'sortOrder' => null,
                'default' => $field->data->default ?? '',
                'isEditable' => !$this->isSystemField($field),
                'isFullyEditable' => false, // Not fully editable when editing module
                'options' => [],
                'data' => $field->data
            ];

            $fields[] = $field;
        }

        return $fields;
    }

    private function isSystemField($field)
    {
        return in_array($field->name, ['created_at', 'updated_at', 'created_by', 'domain']);
    }

    private function deleteModule()
    {
        $this->deleteModuleFromDatabase();
        $this->deleteModuleFiles();
        $this->deleteModuleTable();

        $this->loadCrudModules();
    }

    private function deleteModuleFromDatabase()
    {
        $module = Module::find($this->deletedModuleId);
        $module->delete();
    }

    private function deleteModuleTable()
    {
        if ($this->structure['table']) {
            Schema::dropIfExists($this->structure['table']);
        }
    }

    private function noticeModuleStructureChanged()
    {
        $this->emit('structureChanged', $this->structure);
    }
}
