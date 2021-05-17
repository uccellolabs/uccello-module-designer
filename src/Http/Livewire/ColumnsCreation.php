<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Uccello\ModuleDesigner\Support\Traits\FieldColors;
use Uccello\ModuleDesigner\Support\Traits\HasUitype;
use Uccello\ModuleDesigner\Support\Traits\ModuleInstaller;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;
use Uccello\ModuleDesigner\Support\Traits\TableCreator;

class ColumnsCreation extends Component
{
    use StepManager, StructureManager, FieldColors, HasUitype, ModuleInstaller, TableCreator;

    public $blocks;
    public $fields;
    public $newColumn;
    public $columnNameExists;

    protected $listeners = [
        'stepChanged' => 'onStepChanged',
        'structureChanged' => 'onStructureChanged'
    ];

    public function mount()
    {
        $this->fields = collect();
    }

    public function updatedNewColumn()
    {
        $this->columnNameExists = false;
    }

    public function onStructureChanged($structure)
    {
        $this->structure = $structure;

        $this->blocks = collect();
        $this->fields = collect();
        foreach ($structure['tabs'] as $tab) {
            foreach ($tab['blocks'] as $block) {
                $this->blocks[] = $block;

                foreach ($block['fields'] as $field) {
                    $this->fields[] = $field;
                }
            }
        }
    }

    public function render()
    {
        return view('module-designer::livewire.columns-creation');
    }

    public function addSystemField($label)
    {
        $this->newColumn = $label;

        $this->createField(true);
    }

    public function createField($systemField = false)
    {
        if (!$systemField && empty($this->newColumn)) {
            return;
        }

        $fieldName = Str::slug($this->newColumn, '_');

        if ($this->fields->where('name', $fieldName)->count() > 0) {
            $this->columnNameExists = true;
            return;
        }

        $field = [
            'block_uuid' => $systemField === true ? $this->getSystemBlockUuid() : $this->getFirstBlockUuid(),
            'label' => $this->newColumn,
            'name' => $fieldName,
            'lastName' => null,
            'color' => $this->getColor(count($this->fields)),
            'isRequired' => false,
            'isLarge' => false,
            'isEditable' => true,
            'default' => '',
            'isDisplayedInListView' => true,
            'uitype' => 'text',
            'displaytype' => 'everywhere',
            'sortOrder' => null,
            'filterSequence' => $this->fields->count(),
            'sequence' => $this->fields->count(),
            'options' => []
        ];

        $this->fields[] = $field;
        $this->structure['tabs'][0]['blocks'][0]['fields'][] = $field;

        $this->clearNewColumnField();

        $this->emitStructureChangedEvent($this->structure);
    }

    public function updateColumnsOrder($sortedFields)
    {
        foreach ($sortedFields as $sortedField) {
            $this->fields = $this->fields->map(function ($field) use ($sortedField) {
                if ($field['name'] === $sortedField['value']) {
                    $field['filterSequence'] = $sortedField['order'] - 1;
                    $field['sequence'] = $sortedField['order'] - 1;
                }
                return $field;
            });
        }
    }

    public function toggleIsDisplayedInListView($fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['isDisplayedInListView'] = !$field['isDisplayedInListView'];
            }

            return $field;
        });
    }

    public function toggleFilterSortOrder($fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                if ($field['sortOrder'] === 'asc') {
                    $field['sortOrder'] = 'desc';
                } elseif ($field['sortOrder'] === 'desc') {
                    $field['sortOrder'] = null;
                } else {
                    $field['sortOrder'] = 'asc';
                }
            } else {
                $field['sortOrder'] = null;
            }

            return $field;
        });
    }

    public function deleteField($fieldName)
    {
        $this->fields = $this->fields->filter(function ($field) use ($fieldName) {
            if (!$this->isSameFieldName($field, $fieldName)) {
                return $field;
            }
        });

        // TODO: Delete column from database
    }

    public function incrementStep()
    {
        // if ($this->isConfiguringFields()) {
            $this->createOrUpdateModule();
            $this->updateBlocksAndFields();
            $this->createOrUpdateTable();
        // }

        $this->changeStep($this->step + 1);
    }

    private function getSystemBlockUuid()
    {
        $uuid = null;

        foreach ($this->structure['tabs'] as $tab) {
            foreach ($tab['blocks'] as $block) {
                if ($block['label'] === 'block.system') {
                    $uuid = $block['uuid'];
                    break;
                }
            }
        }

        return $uuid;
    }

    private function getFirstBlockUuid()
    {
        return $this->structure['tabs'][0]['blocks'][0]['uuid'];
    }

    private function clearNewColumnField()
    {
        $this->newColumn = '';
    }

    private function isSameFieldName($field, $fieldName)
    {
        return $field['name'] === $fieldName;
    }
}
