<?php

namespace Uccello\ModuleDesigner\Support\Traits;

use Illuminate\Support\Facades\File;

trait LanguageFileCreator
{
    private function createOrUpdateLanguageFile()
    {
        if (!$this->languageFileExists()) {
            $this->createLanguageFile();
        } else {
            $this->updateLanguageFile();
        }
    }

    private function deleteLanguageFile()
    {
        $filePath = $this->getLanguageFilePath();
        if ($this->languageFileExists()) {
            unlink($filePath);
        }
    }

    private function languageFileExists()
    {
        return File::exists($this->getLanguageFilePath());
    }

    private function getLanguageFilePath()
    {
        $localeDirectoryPath = $this->getLocaleDirectoryPath();
        $languageFileName = $this->getLanguageFileName();

        return "$localeDirectoryPath/$languageFileName";
    }

    private function getLocaleDirectoryPath()
    {
        $packagePath = $this->getPackagePath();
        $locale = $this->getApplicationLocale();

        return "$packagePath/resources/lang/$locale";
    }

    private function getLanguageFileName()
    {
        return $this->structure['name'] . '.php';
    }

    private function getApplicationLocale()
    {
        return config('app.locale');
    }

    private function createLanguageFile()
    {
        $this->createLocaleDirectoryIfNotExists();

        $filePath = $this->getLanguageFilePath();
        $fileContent = $this->generateLanguageContent();

        File::put($filePath, $fileContent);
    }

    private function createLocaleDirectoryIfNotExists()
    {
        File::ensureDirectoryExists($this->getLocaleDirectoryPath());
    }

    private function generateLanguageContent()
    {
        $stubContent = $this->getLanguageStubContent();
        $encodedTranslations = $this->getEncodedTranslations();

        return str_replace('// %translations%', $encodedTranslations, $stubContent);
    }

    private function getEncodedTranslations()
    {
        $translations = $this->getTranslations();

        $encodedTranslations = $this->arrayReadableEncode($translations);

        // Replace first "[" and last "]" to allow user to add other translations before of after
        $encodedTranslations = preg_replace('`^\[`', '', $encodedTranslations);
        $encodedTranslations = preg_replace('`\]$`', '', $encodedTranslations);
        $encodedTranslations = preg_replace('`\]\n`', "],\n", $encodedTranslations);

        return $encodedTranslations;
    }

    private function getTranslations()
    {
        $moduleTranslations = $this->getModuleTranslations();
        $blockTranslations = []; //$this->getBlockTranslations();
        $fieldTranslations = []; //$this->getFieldTranslations();
        $relatedlistTranslations = []; //$this->getRelatedlistTranslations();

        return array_merge($moduleTranslations, $blockTranslations, $fieldTranslations, $relatedlistTranslations);
    }

    private function getModuleTranslations()
    {
        return [
            $this->structure['name'] => $this->structure['label'],
            $this->structure['name'].'_singular' => $this->structure['label_singular']
        ];
    }

    private function getBlockTranslations()
    {
        $translations = [
            'block' => []
        ];

        foreach ($this->blocks as $block) {
            $block = (object) $block;
            $key = str_replace('block.', '', $block->label);
            $translations['block'][$key] = $block->translation;
        }

        return $translations;
    }

    private function getFieldTranslations()
    {
        $translations = [
            'field' => []
        ];

        foreach ($this->fields as $field) {
            $field = (object) $field;
            $translations['field'][$field->name] = $field->label;

            $translationsGeneratedByUitype = $this->getTranslationsGeneratedByUitype($field);

            if ($translationsGeneratedByUitype) {
                $translations = array_merge($translations, $translationsGeneratedByUitype);
            }
        }

        return $translations;
    }

    private function getTranslationsGeneratedByUitype($field)
    {
        $bundle = $this->makeBundle($field);
        $uitype = $this->getFieldUitype($this->toArray($field));

        $formattedFieldData = (new ($uitype->class))->getFormattedFieldDataAndTranslationFromOptions($bundle);

        return !empty($formattedFieldData['translation']) ? $formattedFieldData['translation'] : null;
    }

    private function getRelatedlistTranslations()
    {
        return []; // TODO: Complete
    }

    private function getLanguageStubContent()
    {
        $stubFilePath = realpath(__DIR__.'/../../../resources/stubs/language.stub');

        return File::get($stubFilePath);
    }

    private function updateLanguageFile()
    {
        $filePath = $this->getLanguageFilePath();
        $fileContent = $this->getLanguageFileDiffContent();

        File::put($filePath, $fileContent);
    }

    private function getLanguageFileDiffContent()
    {
        $currentLanguageContent = $this->getCurrentLanguageContent();
        $newLanguageContent = $this->generateLanguageContent();

        return $this->getFileDiffContent($currentLanguageContent, $newLanguageContent);
    }

    private function getCurrentLanguageContent()
    {
        $filePath = $this->getLanguageFilePath();

        return File::get($filePath);
    }
}
