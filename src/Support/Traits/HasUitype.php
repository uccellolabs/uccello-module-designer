<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use stdClass;

trait HasUitype
{
    public function changeUitype($fieldName)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field = $this->getRefreshedField($field);
            }
            return $field;
        });
    }

    public function addRowToFieldOptionArray($fieldName, $optionKey)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName, $optionKey) {
            if ($this->isSameFieldName($field, $fieldName)) {
                $field['data'][$optionKey][] = ['value' => '', 'label' => ''];
            }

            return $field;
        });
    }

    public function deleteRowFromFieldOptionArray($fieldName, $optionKey, $index)
    {
        $this->fields = $this->fields->map(function ($field) use ($fieldName, $optionKey, $index) {
            if ($this->isSameFieldName($field, $fieldName)) {
                unset($field['data'][$optionKey][$index]);
            }

            return $field;
        });
    }

    public function reloadFieldOptions($index)
    {
        $field = $this->fields[$index];

        $this->fields[$index] = $this->getRefreshedField($field, false);
    }

    private function getRefreshedField($field, $clearData = true)
    {
        $field['options'] = $this->getUitypeFieldOptions($field);

        if ($clearData === true) {
            $field['data'] = null;
        }

        $field = $this->updateFieldOptions($field);

        return $field;
    }

    private function getUitypeFieldOptions($field)
    {
        $options = $this->getFieldOptionsAccordingToUitype($field);

        foreach ($options as $i => $option) {
            foreach ($option as $key => $value) {
                if ($this->isClosure($value)) {
                    $options[$i][$key] = call_user_func($value);
                }
            }
        }

        return $options;
    }

    private function getFieldOptionsAccordingToUitype($field)
    {
        $bundle = $this->makeBundle($field);
        $uitypeInstance = $this->getUitypeInstance($field);

        return $uitypeInstance->getFieldOptions($bundle);
    }

    private function makeBundle($field)
    {
        $bundle = new stdClass;
        $bundle->field = (object) $field;
        $bundle->inputFields = collect($this->fields);

        return $bundle;
    }

    private function getUitypeInstance($field)
    {
        $uitype = $this->getFieldUitype($field);

        return (new ($uitype->class));
    }

    private function getFieldUitype($field)
    {
        return uitype($field['uitype']);
    }

    private function isClosure($value)
    {
        return $value instanceof \Closure;
    }

    private function updateFieldOptions($field)
    {
        foreach ($field['options'] as $option) {
            $field = $this->updateFieldDataIfOptionHasDefaultValue($field, $option);

            $field = $this->addFirstRowForOptionOfTypeArray($field, $option);
        }

        return $field;
    }

    private function updateFieldDataIfOptionHasDefaultValue($field, $option)
    {
        if ($this->isOptionDefaultValueDefined($option)) {
            $field['data'][$option['key']] = $option['default'];
        }

        return $field;
    }

    private function isOptionDefaultValueDefined($option)
    {
        return isset($option['default']);
    }

    private function addFirstRowForOptionOfTypeArray($field, $option)
    {
        if ($this->isOptionOfTypeArray($option)) {
            if ($this->hasOptions($field)) {
                $choices = $field['data'][$option['key']];

                // Retrieve value and label
                $field['data'][$option['key']] = [];
                foreach ($choices as $choice) {
                    $field['data'][$option['key']][] = [
                        'value' => str_replace($field['name'].'.', '', $choice),
                        'label' => uctrans($choice, ucmodule($this->structure['name']))
                    ];
                }
            } else {
                // Create new row
                $defaultRow = new stdClass;
                $defaultRow->value = '';
                $defaultRow->label = '';

                $field['data'][$option['key']] = [
                    $defaultRow
                ];
            }
        }

        return $field;
    }

    private function isOptionOfTypeArray($option)
    {
        return $option['type'] === 'array';
    }

    private function hasOptions($field)
    {
        return !empty($field['data']) && !empty($field['data']['choices']);
    }
}
