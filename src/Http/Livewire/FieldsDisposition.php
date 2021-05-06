<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;

class FieldsDisposition extends Component
{
    use StepManager, StructureManager;

    public $blocks;

    protected $listeners = ['stepChanged', 'structureChanged'];

    public function mount()
    {
        $this->blocks = collect();
    }

    public function render()
    {
        return view('module-designer::livewire.fields-disposition');
    }

    public function structureChanged($structure)
    {
        $this->structure = $structure;

        foreach ($structure['tabs'] as $tab) {
            foreach ($tab['blocks'] as $block) {
                $this->blocks[] = $block;
            }
        }
    }

    public function createBlock()
    {
        $index = $this->getNexBlockIndex();

        $this->addBlock([
            'uuid' => Str::uuid(),
            'label' => 'block.block'.$index,
            'translation' => 'Block '.$index,
            'icon' => null,
            'sequence' => $this->blocks->count(),
        ]);
    }

    public function deleteBlock($index)
    {
        unset($this->blocks[$index]);

        // $this->checkIfThereAreAvailableFields();
    }

    public function updateBlockOrder($sortedBlocks)
    {
        foreach ($sortedBlocks as $sortedBlock) {
            $this->blocks = $this->blocks->map(function ($block) use ($sortedBlock) {
                if ($block['uuid'] === $sortedBlock['value']) {
                    $block['sequence'] = $sortedBlock['order'] - 1;
                }
                return $block;
            });
        }
    }

    public function updateBlockFieldOrder($sortedBlocks)
    {
        foreach ($sortedBlocks as $sortedBlock) {
            foreach ($sortedBlock['items'] as $sortedField) {
                $this->mapFields(function ($field) use ($sortedBlock, $sortedField) {
                    if ($field['name'] === $sortedField['value']) {
                        $field['sequence'] = $sortedField['order'] - 1;
                        $field['block_uuid'] = $sortedBlock['value'];
                    }
                    return $field;
                });
            }
        }

        // dd($this->fields);
    }

    public function removeFieldFromBlock($fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['block_uuid'] = null;
            }
            return $field;
        });

        $this->checkIfThereAreAvailableFields();
    }

    public function addFieldToBlock($blockUuid, $fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($blockUuid, $fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['block_uuid'] = $blockUuid;
            }

            return $field;
        });

        $this->checkIfThereAreAvailableFields();
    }

    private function isSameFieldName($field, $fieldName)
    {
        return $field['name'] === $fieldName;
    }

    private function addBlock($params)
    {
        $this->blocks[] = $params;
    }

    private function getNexBlockIndex()
    {
        return $this->blocks->count() + 1;
    }
}
