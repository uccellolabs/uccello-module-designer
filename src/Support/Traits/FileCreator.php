<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Facades\File;

trait FileCreator
{
    private function createOrUpdateModuleFiles()
    {
        $this->createOrUpdateModelFile();
        $this->createOrUpdateLanguageFile();
        $this->createOrUpdateMigrationFile();
        $this->createOrUpdateUccelloManifestFile();
    }

    private function createOrUpdateModelFile()
    {
        if (!$this->modelFileExists()) {
            $this->createModelFile();
        } else {
            $this->updateModelFile();
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

    private function getPackagesBaseDirectory()
    {
        return rtrim(config('module-designer.packages_directory'), '/');
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

        $namespace = $this->getPackageNamespace();

        $content = str_replace(
            [
                '// %namespace%',
                'ClassName',
                '%table_name%',
                '// %relations%'
            ],
            [
                "namespace $namespace;",
                $this->getModelClassFromModuleName(),
                $this->getModuleTableName(),
                '', // TODO: generate relations
            ],
            $stubContent
        );

        return $content;
    }

    private function getModelStubContent()
    {
        $stubFilePath = realpath(__DIR__.'/../../../resources/stubs/model.stub');

        // dd(__DIR__.'/../../../resources/stubs/model.stub');

        return File::get($stubFilePath);
    }

    private function getModuleTableName()
    {
        return $this->structure['table'];
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

        preg_match('`// MODULE DESIGNER - START(.+?)// MODULE DESIGNER - END`s', $newModelContent, $matches);

        $generatedContentToKeep = $matches[0];

        $diffContent = preg_replace(
            '`// MODULE DESIGNER - START(.+?)// MODULE DESIGNER - END`s',
            $generatedContentToKeep,
            $currentModelContent
        );

        return $diffContent;
    }

    private function getCurrentModelContent()
    {
        $filePath = $this->getModelFilePath();

        return File::get($filePath);
    }

    private function createOrUpdateLanguageFile()
    {
        //
    }

    private function createOrUpdateMigrationFile()
    {
        //
    }

    private function createOrUpdateUccelloManifestFile()
    {
        //
    }
}
