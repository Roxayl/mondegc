<?php

namespace App\View\Components\ChapterEntry;

use App\Models\ChapterEntry;
use App\View\Components\BaseComponent;

abstract class BaseMediaEntry extends BaseComponent
{
    public ChapterEntry $entry;

    public function __construct(ChapterEntry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Génère les données d'un média.
     *
     * @param array<string, array> $parameters
     * @return array
     */
    public abstract function generateData(array $parameters): array;
}
