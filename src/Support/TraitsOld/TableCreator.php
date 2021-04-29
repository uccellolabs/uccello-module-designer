<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Uccello\Core\Models\Field;

trait TableCreator
{
    public function createOrUpdateTable()
    {
        $this->retrieveTableName();

        if ($this->mustTableBeRenamed()) {
            $this->renameTable();
        }

        if ($this->tableExists()) {
            $this->updateTable();
        } else {
            $this->createTable();
        }
    }

    private function retrieveTableName()
    {
        $this->structure['table'] = Str::plural(str_replace('-', '_', $this->structure['name']));
    }

    private function mustTableBeRenamed()
    {
        return !empty($this->structure['lastTable']) && $this->structure['table'] !== $this->structure['lastTable'];
    }

    private function renameTable()
    {
        Schema::rename($this->structure['lastTable'], $this->structure['table']);
        $this->structure['lastTable'] = $this->structure['table'];
    }

    private function tableExists()
    {
        return Schema::hasTable($this->structure['table']);
    }

    private function createTable()
    {
        if ($this->tableNameNotDefined()) {
            return;
        }

        Schema::create($this->structure['table'], function (Blueprint $table) {
            $table->increments('id');
            foreach ($this->getSortedFields() as $field) {
                $this->createTableColumn($table, $field); // e.g: $table->string('field_name')->nullable()
            }
            $table->softDeletes();
        });
    }

    private function tableNameNotDefined()
    {
        return empty($this->structure['table']);
    }

    private function updateTable()
    {
        Schema::table($this->structure['table'], function (Blueprint $table) {
            foreach ($this->getSortedFields() as $field) {
                $this->createOrUpdateTableColumn($table, $field); // e.g: $table->string('field_name')->change()
            }
        });
    }

    private function getSortedFields()
    {
        return $this->fields->sortBy('sequence')->map(function ($field) {
            return (object) $field;
        });
    }

    private function createOrUpdateTableColumn(Blueprint $table, $field)
    {
        if ($this->tableColumnExists($table, $field)) {
            $this->updateTableColumn($table, $field);
        } else {
            $this->createTableColumn($table, $field);
        }
    }

    private function tableColumnExists(Blueprint $table, $field)
    {
        $tableName = $table->getTable();

        $fakeField = $this->getFakeField($field);
        $uitypeClass = uitype($field->uitype)->class;
        $column = (new $uitypeClass)->getDefaultDatabaseColumn($fakeField);

        return Schema::hasColumn($tableName, $column);
    }

    private function createColumnInExistingTable($field)
    {
        Schema::table($this->structure['table'], function (Blueprint $table) use ($field) {
            $this->createTableColumn($table, $field);
        });
    }

    private function updateColumnInExistingTable($field)
    {
        Schema::table($this->structure['table'], function (Blueprint $table) use ($field) {
            $this->updateTableColumn($table, $field);
        });
    }

    private function createTableColumn(Blueprint $table, $field)
    {
        $field = (object) $field;

        $fakeField = $this->getFakeField($field);

        $column = uitype($field->uitype)->createFieldColumn($fakeField, $table);
        $column->nullable();

        return $column;
    }

    private function updateTableColumn(Blueprint $table, $field)
    {
        $field = (object) $field;

        $fakeField = $this->getFakeField($field);

        $column = uitype($field->uitype)->createFieldColumn($fakeField, $table);
        $column->change();

        return $column;
    }

    private function getFakeField($field)
    {
        $field = (object) $field;

        $fakeField = new Field();
        $fakeField->name = $field->name;
        $fakeField->uitype_id = uitype($field->uitype)->id;
        $fakeField->displaytype_id = displaytype($field->displaytype)->id;

        return $fakeField;
    }
}
