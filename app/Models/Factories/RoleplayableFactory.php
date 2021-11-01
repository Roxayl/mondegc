<?php

namespace App\Models\Factories;

use App\Models\Contracts\Roleplayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RoleplayableFactory
{
    /**
     * Liste des classes roleplayables.
     */
    public const roleplayables = [
        'organisation',
        'pays',
        'ville',
    ];

    /**
     * Cherche une instance d'un {@link Roleplayable}.
     * @param string $type Type du roleplayable. Peut prendre les valeurs spécifiées dans la constante
     *                    {@link roleplayables}.
     * @param int    $id   Identifiant du roleplayable.
     * @return Roleplayable|null Renvoie null lorsque le roleplayable n'existe pas.
     */
    public static function find(string $type, int $id): ?Roleplayable
    {
        if(! in_array($type, self::roleplayables)) {
            return null;
        }

        $class = "\App\Models\\". Str::ucfirst($type);

        /** @var Collection $collection */
        $collection = call_user_func([$class, 'find'], [$id]);

        return $collection->first();
    }
}
