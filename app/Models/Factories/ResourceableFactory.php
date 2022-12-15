<?php

namespace Roxayl\MondeGC\Models\Factories;

use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;

class ResourceableFactory
{
    use ModelFactory;

    /**
     * Interface implémentée par les modèles resourçables.
     */
    public const contract = Resourceable::class;

    /**
     * Liste des classes resourçables.
     */
    public const models = [
        Organisation::class,
        Pays::class,
        Ville::class,
    ];
}
