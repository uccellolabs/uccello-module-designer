<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Uccello\ModuleDesignerCore\Models\DesignedModule;

trait HasStep
{
    public $step = 0;

    public function incrementStep()
    {
        $this->handleCurrentStep();

        $this->step++;
    }

    public function changeStep($step)
    {
        $this->handleCurrentStep();

        $this->step = $step;
    }

    private function handleCurrentStep()
    {
        if (!$this->isChoosingAction()) {
            $this->createOrUpdateTableAndModule();
        }

        if ($this->isChoosingAction()) {
            $this->chooseAction();
        } elseif ($this->isConfiguringModuleName()) {
            $this->updateModuleFromStructure();
        } elseif ($this->isCreatingColumns()) {
            $this->createOrUpdateFilter();
        } elseif ($this->isConfiguringFields()) {
            $this->updateBlocksAndFields();
        }
    }

    private function isChoosingAction()
    {
        return $this->step === 0;
    }

    private function isConfiguringModuleName()
    {
        return $this->step === 1;
    }

    private function isCreatingColumns()
    {
        return $this->step === 2;
    }

    private function isConfiguringFields()
    {
        return $this->step === 3 || $this->step === 4;
    }

    private function chooseAction()
    {
        if ($this->action === 'create') {
            $this->createDesignedModule();
        } elseif ($this->action === 'edit') {
            $this->editModule();
        } elseif ($this->action === 'continue') {
            $this->continueDesignedModule();
        }
    }

    private function editModule()
    {
        // TODO
    }

    private function continueDesignedModule()
    {
        $this->designedModule = DesignedModule::find($this->editedDesignedModuleId);

        $this->structure = $this->toArray($this->designedModule->data);
        $this->moduleLabel = $this->structure['label'];

        $this->buildOptimizedStructure();

        $this->checkIfThereAreAvailableFields();
    }
}
