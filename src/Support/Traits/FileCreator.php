<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait FileCreator
{
    use ModelFileCreator;
    use LanguageFileCreator;

    private function createOrUpdateModuleFiles()
    {
        $this->createOrUpdateModelFile();
        $this->createOrUpdateLanguageFile();
        // $this->createOrUpdateMigrationFile(); // TODO: Add
        // $this->createOrUpdateUccelloManifestFile(); // TODO: Add
    }

    private function getPackagesBaseDirectory()
    {
        return rtrim(config('module-designer.packages_directory'), '/');
    }

    private function getFileDiffContent($currentContent, $newContent)
    {
        preg_match('`// MODULE DESIGNER - START(.+?)// MODULE DESIGNER - END`s', $newContent, $matches);

        $generatedContentToKeep = $matches[0];

        $diffContent = preg_replace(
            '`// MODULE DESIGNER - START(.+?)// MODULE DESIGNER - END`s',
            $generatedContentToKeep,
            $currentContent
        );

        return $diffContent;
    }

    private function arrayReadableEncode($in, $indent = 0, $from_array = false)
    {
        $_escape = function ($str) {
            return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $str);
        };

        $out = '';

        foreach ($in as $key => $value) {
            $out .= str_repeat("    ", $indent + 1);
            $out .= "'".$_escape((string)$key)."' => ";

            if (is_object($value) || is_array($value)) {
                $out .= $this->arrayReadableEncode($value, $indent + 1);
            } elseif (is_bool($value)) {
                $out .= $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $out .= 'null';
            } elseif (is_string($value)) {
                $out .= "'" . $_escape($value) ."'";
            } else {
                $out .= $value;
            }

            $out .= ",\n";
        }

        if (!empty($out)) {
            $out = substr($out, 0, -2);
        }

        $out = "[\n" . $out;
        $out .= "\n" . str_repeat("    ", $indent) . "]";

        return $out;
    }
}
