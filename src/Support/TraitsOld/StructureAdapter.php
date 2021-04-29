<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait StructureAdapter
{
    private function buildDesignedModuleStructure()
    {
        $structure = $this->structure;
        $structure['step'] = $this->step;

        $tab = $this->initTabStructure();

        foreach ($this->blocks as $block) {
            $block = (object) $block;
            $block->fields = $this->getFieldsWithSameBlockUuid($block);
            $tab['blocks'][] = $block;
        }

        $structure['tabs'][0] = $tab;

        $structure['blocks'] = collect($this->blocks);
        $structure['fields'] = collect($this->fields);

        return $structure;
    }

    private function initTabStructure()
    {
        $tab = $this->structure['tabs'][0];
        $tab['blocks'] = [];

        return $tab;
    }

    private function getFieldsWithSameBlockUuid($block)
    {
        $fields = $this->fields->filter(function ($field) use ($block) {
            $field = (object) $field;
            return $field->block_uuid === $block->uuid;
        });

        return $fields->toArray();
    }

    private function buildOptimizedStructure()
    {
        $this->initBlocksOptimizedStructure();
        $this->initFieldsOptimizedStructure();
        $this->initStepOptimizedStructure();
        $this->addBlocksAndFieldsToOptimizedStructure();
        $this->deleteNotUsefulDataFromOptimizedStructure();
    }

    private function initBlocksOptimizedStructure()
    {
        $blocks = $this->structure['blocks'] ?? [];
        $this->blocks = collect($blocks);
    }

    private function initFieldsOptimizedStructure()
    {
        $fields = $this->structure['fields'] ?? [];
        $this->fields = collect($fields);
    }

    private function initStepOptimizedStructure()
    {
        $this->step = $this->structure['step'] ?? 0;
    }

    private function addBlocksAndFieldsToOptimizedStructure()
    {
        foreach ($this->structure['tabs'] as $tab) {
            $tab = (object) $tab;
            foreach ($tab->blocks as $i => $block) {
                $block = (object) $block;

                $this->addBlockFieldsToOptimizedStructure($block);

                $this->addBlockToOptimizedStructure($tab, $block);
            }
        }
    }

    private function addBlockFieldsToOptimizedStructure($block)
    {
        if (!empty($block->fields)) {
            foreach ($block->fields as $field) {
                $field = (object) $field;
                $this->addFieldToOptimizedStructure($block, $field);
            }
        }
    }

    private function addFieldToOptimizedStructure($block, $field)
    {
        if (!$this->fieldExists($field)) {
            // Uuid is useful to retrieve block
            $field->block_uuid = $block->uuid;

            $this->fields[] = $field;
        }
    }

    private function fieldExists($field)
    {
        return $this->fields->pluck('name')->contains($field->name);
    }

    private function addBlockToOptimizedStructure($tab, $block)
    {
        if (!$this->blockExists($block)) {
            // Uuid is useful to retrieve tab
            $block->tab_uuid = $tab->uuid;

            // We don't need to remember fields list for each block. We use uuid for retrieve a block from a field
            unset($block->fields);

            $this->blocks[] = $block;
        }
    }

    private function blockExists($block)
    {
        return $this->blocks->pluck('uuid')->contains($block->uuid);
    }

    /**
     * As blocks and fields are stored in $this->blocks and $this->fields properties,
     * we can delete it from $this->structure. These data will be added before to be
     * saved into the database.
     *
     * @return void
     */
    private function deleteNotUsefulDataFromOptimizedStructure()
    {
        unset($this->structure['tabs'][0]['blocks']);
        unset($this->structure['blocks']);
        unset($this->structure['fields']);
    }

    private function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}
