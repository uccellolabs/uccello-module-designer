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
                    $field['sequence'] = $sortedField['order'] - 1;
                }
                return $field;
            });
        }
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
        $field['last_name'] = $field['name'];

        return $field;
    }

    private function wasFieldNameChanged($field)
    {
        return !empty($field['last_name']) && $field['name'] !== $field['last_name'];
    }

    private function checkIfThereAreAvailableFields()
    {
        $this->areAvailableFields = false;
        foreach ($this->fields as $field) {
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
