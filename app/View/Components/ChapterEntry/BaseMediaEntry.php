<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Roxayl\MondeGC\Models\ChapterEntry;
use Roxayl\MondeGC\View\Components\BaseComponent;

abstract class BaseMediaEntry extends BaseComponent
{
    public function __construct(public ChapterEntry $entry)
    {
    }

    /**
     * Génère les données d'un média.
     *
     * @param array<string, array> $parameters
     * @return array Données du média.
     */
    public abstract function generateData(array $parameters): array;
}
