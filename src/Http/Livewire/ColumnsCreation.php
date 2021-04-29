<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Uccello\ModuleDesigner\Support\Traits\FieldColors;
use Uccello\ModuleDesigner\Support\Traits\HasField;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;

class ColumnsCreation extends Component
{
    use StepManager, StructureManager, FieldColors;

    public $fields;
    public $newColumn;

    protected $listeners = ['stepChanged', 'structureChanged'];

    public function mount()
    {
        $this->fields = collect();
    }

    public function render()
    {
        return view('module-designer::livewire.columns-creation');
    }

    public function createField()
    {
        //FIXME: If column exists, display error or offer to link to the column

        $this->fields[] = [
            'block_uuid' => $this->getFirstBlockUuid(),
            'label' => $this->newColumn,
            'name' => Str::slug($this->newColumn, '_'),
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

        $this->clearNewColumnField();
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
    }

    private function getFirstBlockUuid()
    {
        $this->structure['tabs'][0]['blocks'][0];
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
