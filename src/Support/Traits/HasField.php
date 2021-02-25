<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Str;

trait HasField
{
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

    public function createField()
    {
        $this->addField([
            'block_uuid' => $this->getFirstBlockUuid(),
            'label' => $this->column,
            'name' => Str::slug($this->column, '_'),
            'lastName' => null,
            'color' => $this->getColor(),
            'isRequired' => false,
            'isLarge' => false,
            'isDisplayedInListView' => true,
            'uitype' => 'text',
            'displaytype' => 'everywhere',
            'sortOrder' => null,
            'filterSequence' => $this->fields->count(),
            'sequence' => $this->fields->count(),
            'options' => []
        ]);

        $this->clearNewColumnField();
        $this->checkIfThereAreAvailableFields();
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
        $blockUuid = $this->blocks[$index]['uuid'];

        $this->mapFields(function ($field) use ($blockUuid) {
            if ($field['block_uuid'] === $blockUuid) {
                $field['block_uuid'] = null;
            }

            return $field;
        });

        unset($this->blocks[$index]);

        $this->checkIfThereAreAvailableFields();
    }

    public function toggleIsDisplayedInListView($fieldName)
    {
        $this->mapFields(function ($field) use ($fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['isDisplayedInListView'] = !$field['isDisplayedInListView'];
            }

            return $field;
        });
    }

    public function toggleFilterSortOrder($fieldName)
    {
        $this->mapFields(function ($field) use ($fieldName) {
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

    public function toggleLarge($fieldName)
    {
        $this->mapFields(function ($field) use ($fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['isLarge'] = !$field['isLarge'];
            }

            return $field;
        });
    }

    private function getFirstBlockUuid()
    {
        return !empty($this->blocks[0]) ? $this->blocks[0]['uuid'] : null;
    }

    private function getNexBlockIndex()
    {
        return $this->blocks->count() + 1;
    }

    private function clearNewColumnField()
    {
        $this->column = '';
    }

    public function updateColumnsOrder($sortedFields)
    {
        foreach ($sortedFields as $sortedField) {
            $this->mapFields(function ($field) use ($sortedField) {
                if ($field['name'] === $sortedField['value']) {
                    $field['filterSequence'] = $sortedField['order'] - 1;
                    $field['sequence'] = $sortedField['order'] - 1;
                }
                return $field;
            });
        }
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
        $this->mapFields(function ($field) use ($fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['block_uuid'] = null;
            }
            return $field;
        });

        $this->checkIfThereAreAvailableFields();
    }

    public function addFieldToBlock($blockUuid, $fieldName)
    {
        $this->mapFields(function ($field) use ($blockUuid, $fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['block_uuid'] = $blockUuid;
            }

            return $field;
        });

        $this->checkIfThereAreAvailableFields();
    }

    private function mapFields(\Closure $method)
    {
        $this->fields = $this->fields->map($method);
    }

    private function isSameFieldName($field, $fieldName)
    {
        return $field['name'] === $fieldName;
    }

    private function getColor()
    {
        return $this->colors[count($this->fields) % count($this->colors)];
    }

    private function addBlock($params)
    {
        $this->blocks[] = $params;
    }

    private function addField($field)
    {
        $this->createOrUpdateColumnInExistingTable($field);

        $field = $this->getFieldWithLastNameUpdated($field);

        $this->fields[] = $field;
    }

    private function createOrUpdateColumnInExistingTable($field)
    {
        if ($this->wasFieldNameChanged($field)) {
            $this->updateColumnInExistingTable($field);
        } else {
            $this->createColumnInExistingTable($field);
        }
    }

    private function getFieldWithLastNameUpdated($field)
    {
        $field['lastName'] = $field['name'];

        return $field;
    }

    private function wasFieldNameChanged($field)
    {
        return !empty($field['lastName']) && $field['name'] !== $field['lastName'];
    }

    private function checkIfThereAreAvailableFields()
    {
        $this->areAvailableFields = false;
        foreach ($this->fields as $field) {
            $field = (object) $field;
            if (!$this->isFieldPartOfBlock($field)) {
                $this->areAvailableFields = true;
                break;
            }
        }
    }

    private function isFieldPartOfBlock($field)
    {
        return !empty($field->block_uuid);
    }
}
