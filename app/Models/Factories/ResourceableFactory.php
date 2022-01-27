<?php

namespace App\Models\Factories;

use App\Models\Contracts\Resourceable;
use App\Models\Organisation;
use App\Models\Pays;
use App\Models\Ville;

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
