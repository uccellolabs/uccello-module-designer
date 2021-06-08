<?php

namespace Uccello\ModuleDesigner\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Uccello\Core\Models\Module;
use Uccello\ModuleDesigner\Support\Traits\FieldColors;
use Uccello\ModuleDesigner\Support\Traits\FileCreator;
use Uccello\ModuleDesigner\Support\Traits\HasUitype;
use Uccello\ModuleDesigner\Support\Traits\ModuleInstaller;
use Uccello\ModuleDesigner\Support\Traits\StepManager;
use Uccello\ModuleDesigner\Support\Traits\StructureManager;
use Uccello\ModuleDesigner\Support\Traits\TableCreator;

class ColumnsCreation extends Component
{
    use StepManager, StructureManager, FieldColors, HasUitype, FileCreator, ModuleInstaller, TableCreator;

    public $blocks;
    public $fields;
    public $newColumn;
    public $fieldNameReserved;
    public $fieldNameUsed;

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
        $this->fieldNameReserved = false;
        $this->fieldNameUsed = false;
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

    // A la validation de l'étape : mettre à jour le filtre

    public function addField()
    {
        if ($this->isFieldNameValid() && $this->isFieldNameAvailable()) {
            $this->createField([
                'block_uuid' => $this->getFirstBlockUuid(),
                'label' => $this->newColumn,
                'name' => $this->getFieldName(),
                'color' => $this->getColor(count($this->fields)),
                'isRequired' => false,
                'isLarge' => false,
                'isEditable' => true,
                'isFullyEditable' => true,
                'isSystemField' => false,
                'isDisplayedInListView' => true,
                'uitype' => 'text',
                'displaytype' => 'everywhere',
                'default' => '',
                'sortOrder' => null,
                'filterSequence' => $this->fields->count(),
                'sequence' => $this->fields->count(),
                'options' => []
            ]);

            $this->clearNewColumnField();
        }
    }

    private function createField($field)
    {
        $this->fields[] = $field;
    }

    /**
     * Check if the field name is valid
     *
     * @return boolean
     */
    private function isFieldNameValid()
    {
        return !empty($this->newColumn);
    }

    /**
     * Check if the field name is not reserved and not already used,
     * else display an error message.
     *
     * @return boolean
     */
    private function isFieldNameAvailable()
    {
        $available = false;

        if ($this->isFieldNameReserved()) {
            $this->fieldNameReserved = true;
        } elseif ($this->isFieldNameAlreadyUsed()) {
            $this->fieldNameUsed = true;
        } else {
            $available = true;
        }

        return $available;
    }

    /**
     * Check if the field name is reserved
     *
     * @return boolean
     */
    private function isFieldNameReserved()
    {
        $fieldName = $this->getFieldName();

        return in_array($fieldName, [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            'record_label',
            'domain',
            'created_by'
        ]);
    }

    /**
     * Use slug method to genrate field name
     *
     * @return string
     */
    private function getFieldName()
    {
        return Str::slug($this->newColumn, '_');
    }

    /**
     * Check if the field name is already used
     *
     * @return boolean
     */
    private function isFieldNameAlreadyUsed()
    {
        return $this->fields->filter(function ($field) {
            return $field['name'] === $this->getFieldName();
        })->count() > 0;
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
        if ($this->isCreatingColumns()) {
            // Recréer la structure des blocks et champs
            $this->updateStructureWithFields();

            // Recréer tous les champs
            $this->updateBlocksAndFields();

            // Recréer le filtre all
            $this->createOrUpdateFilter();

            // Mettre à jour le fichier de traduction
            $this->createOrUpdateLanguageFile();

        } elseif ($this->isConfiguringFields()) {
            // Recréer la structure des blocks et champs
            $this->updateStructureWithFields();

            // Recréer tous les champs
            $this->updateBlocksAndFields();

            // Créer les related list 1-N
            // Mettre à jour le modèle avec les relations et les clès étrangères
            // Faire en sorte qu'on ne puis plus modifier le slug et type de champ dès qu'il est créé en base de données
            // TODO: A faire

            // Mettre à jour le fichier de traduction
            $this->createOrUpdateModuleFiles();

            // Créer la table
            $this->createOrUpdateTable();

            // Activer le module sur tous les domaines
            $this->activateModuleInAllDomains();
        } elseif ($this->isConfiguringRecordLabel()) {
            $this->createOrUpdateModelFile();
        }

        $this->changeStep($this->step + 1);
    }

    private function updateStructureWithFields()
    {
        if (!empty($this->structure['tabs'])) {
            foreach ($this->structure['tabs'] as &$tab) {
                foreach ($tab['blocks'] as &$block) {
                    $block['fields'] = $this->fields->filter(function ($field) use ($block) {
                        if ($field['block_uuid'] === $block['uuid']) {
                            return $field;
                        };
                    });
                }
            }

            $this->emitStructureChangedEvent($this->structure);
        }
    }

    private function getSystemBlockUuid()
    {
        $uuid = null;

        if (!empty($this->structure['tabs'])) {
            foreach ($this->structure['tabs'] as $tab) {
                foreach ($tab['blocks'] as $block) {
                    if ($block['label'] === 'block.system') {
                        $uuid = $block['uuid'];
                        break;
                    }
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

    private function activateModuleInAllDomains()
    {
        // $domains = Domain::all();
        // foreach ($domains as $domain) {
        //     $domain->modules()->attach($this->module);
        // }
    }
}
