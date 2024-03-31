<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Factories;

use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;

class RoleplayableFactory
{
    use ModelFactory;

    /**
     * Interface implémentée par les modèles roleplayables.
     */
    public const contract = Roleplayable::class;

    /**
     * Liste des classes roleplayables.
     */
    public const models = [
        Organisation::class,
        Pays::class,
        Ville::class,
    ];
}
