<?php

namespace Uccello\ModuleDesigner\Support\Traits;

trait FieldColors
{
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

    private function getColor($index)
    {
        $colorsCount = count($this->colors);

        return $this->colors[$index % $colorsCount];
    }
}
