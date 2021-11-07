<?php

namespace App\Models\Factories;

use App\Models\Contracts\Roleplayable;
use App\Models\Organisation;
use App\Models\Pays;
use App\Models\Ville;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RoleplayableFactory
{
    /**
     * Liste des classes roleplayables.
     */
    public const roleplayables = [
        Organisation::class,
        Pays::class,
        Ville::class,
    ];

    /**
     * Cherche une instance d'un {@link Roleplayable}.
     * @param string $type Type du roleplayable. Peut prendre les valeurs spécifiées dans la constante
     *                     {@link roleplayables}, ou le nom raccourci (e.g. "pays" pour "App\Models\Pays").
     * @param int    $id   Identifiant du roleplayable.
     * @return Roleplayable|null Renvoie null lorsque le roleplayable n'existe pas.
     */
    public static function find(string $type, int $id): ?Roleplayable
    {
        $class = self::getClassName($type);

        if(! in_array($class, self::roleplayables)) {
            return null;
        }

        /** @var Collection $collection */
        $collection = call_user_func([$class, 'find'], [$id]);

        return $collection->first();
    }

    /**
     * Liste tous les roleplayables d'un certain type.
     * @param string|array|null $types Type des roleplayables à obtenir. Null pour obtenir tous les roleplayables de
     *                                 tous les types.
     * @param string|null       $search Termes de recherche. Null pour donner tous les résultats.
     * @return Collection<int, Roleplayable>|Roleplayable[]
     */
    public static function list($types = null, ?string $search = null): Collection
    {
        $result = collect();

        $classes = [];
        if($types === null) {
            $classes = self::roleplayables;
        } else {
            if(! is_array($types)) {
                $types = [$types];
            }

            foreach($types as $type) {
                $class = self::getClassName($type);
                if(! in_array($class, self::roleplayables)) {
                    continue;
                }
                $classes[] = $class;
            }
        }

        foreach($classes as $class) {
            /** @var \Illuminate\Database\Eloquent\Builder $query */
            $query = call_user_func(['\\' . $class, 'query']);
            if($search !== null) {
                $query->where($class::getNameColumn(), 'like', "%$search%");
            }

            $roleplayables = $query->get();
            $result = $result->merge($roleplayables);
        }

        return $result;
    }

    /**
     * @param string $type
     * @return string
     */
    private static function getClassName(string $type): string
    {
        $namespace = 'App\Models\\';

        if(Str::contains($type, $namespace)) {
            $class = Str::startsWith($type, '\\') ? Str::substr($type, 1) : $type;
        } else {
            $class = $namespace . Str::ucfirst($type);
        }

        return $class;
    }
}
