<?php

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Roxayl\MondeGC\Models\ChapterEntry;
use Roxayl\MondeGC\View\Components\BaseComponent;

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
     * @return array Données du média.
     */
    public abstract function generateData(array $parameters): array;
}
