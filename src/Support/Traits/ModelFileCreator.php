<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Uccello\Core\Models\Module;

trait ModelFileCreator
{
    private function createOrUpdateModelFile()
    {
        if (!$this->modelFileExists()) {
            $this->createModelFile();
        } else {
            $this->updateModelFile();
        }
    }

    private function deleteModelFile()
    {
        $filePath = $this->getModelFilePath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function modelFileExists()
    {
        return File::exists($this->getModelFilePath());
    }

    private function getModelFilePath()
    {
        $packagePath = $this->getPackagePath();
        $modelFileName = $this->getModelFileName();

        return "$packagePath/src/Models/$modelFileName";
    }

    private function getPackagePath()
    {
        if ($this->isModulePartsOfLocalApplication()) {
            $path = '';
        } else {
            $packageName = $this->getPackageName();
            $path = $this->getPackagesBaseDirectory() . '/' .$packageName;
        }

        return base_path($path);
    }

    private function getModelFileName()
    {
        return $this->getModelClassFromModuleName().'.php';
    }

    private function createModelFile()
    {
        $filePath = $this->getModelFilePath();
        $fileContent = $this->generateModelContent();

        File::put($filePath, $fileContent);
    }

    private function generateModelContent()
    {
        $stubContent = $this->getModelStubContent();

        $namespace = $this->getPackageNamespace().'\Models';

        $content = str_replace(
            [
                '// {{namespace}}',
                'ClassName',
                '{{table_name}}',
                '// {{relations}}',
                '// {{recordLabel}}'
            ],
            [
                "namespace $namespace;",
                $this->getModelClassFromModuleName(),
                $this->getModuleTableName(),
                $this->getModuleRelations(),
                $this->getRecordLabelFunction()
            ],
            $stubContent
        );

        return $content;
    }

    private function getModelStubContent()
    {
        $stubFilePath = realpath(__DIR__.'/../../../resources/stubs/model.stub');

        return File::get($stubFilePath);
    }

    private function getModuleTableName()
    {
        return $this->structure['table'];
    }

    private function getModuleRecordLabel()
    {
        $content = $this->getCurrentModelContent();

        preg_match('`// recordLabel:([^\s]+)`', $content, $matches);

        return !empty($matches[1]) ? $matches[1] : 'id';
    }

    private function getRecordLabelFunction()
    {
        $fieldName = $this->structure['recordLabel'] ?? 'id';

        return "    public function getRecordLabelAttribute()\n".
        "    {\n".
        "        return \$this->$fieldName; // recordLabel:$fieldName\n".
        "    }";
    }

    private function getModuleRelations()
    {
        $relations = "";

        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                if ($this->isEntityField($field)) {
                    $modelClass = $this->getRelatedModuleModelClass($field);
                    $plural = $this->getRelatedModuleNamePluralized($field);

                    if ($modelClass && $plural) {
                        $relations .= "\n    public function $plural()\n".
                        "    {\n".
                        "        return \$this->belongsTo(\\$modelClass::class);\n".
                        "    }\n";
                    }
                }
            }
        }

        return $relations;
    }

    private function isEntityField($field)
    {
        return $field['uitype'] === 'entity';
    }

    private function getRelatedModuleModelClass($field)
    {
        $modelClass = null;

        if (optional($field['data'])['module']) {
            $module = Module::where('name', $field['data']['module'])->first();

            if ($module) {
                $modelClass = $module->model_class;
            }
        }

        return $modelClass;
    }

    private function getRelatedModuleNamePluralized($field)
    {
        $name = null;

        if (optional($field['data'])['module']) {
            $module = Module::where('name', $field['data']['module'])->first();

            if ($module) {
                $name = Str::pluralStudly(Str::slug($field['data']['module'], '_'));
            }
        }

        return $name;
    }

    private function updateModelFile()
    {
        $filePath = $this->getModelFilePath();
        $fileContent = $this->getModelFileDiffContent();

        File::put($filePath, $fileContent);
    }

    private function getModelFileDiffContent()
    {
        $currentModelContent = $this->getCurrentModelContent();
        $newModelContent = $this->generateModelContent();

        return $this->getFileDiffContent($currentModelContent, $newModelContent);
    }

    private function getCurrentModelContent()
    {
        $filePath = $this->getModelFilePath();

        return File::get($filePath);
    }
}
