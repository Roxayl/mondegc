<?php

namespace App\Models\Factories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @template M
 */
trait ModelFactory
{
    /**
     * Cherche une instance d'un {@link Model}.
     * @param string $type Type du modèle. Peut prendre les valeurs spécifiées dans la constante
     *                     {@link models}, ou le nom raccourci (e.g. "pays" pour "App\Models\Pays").
     * @param int    $id   Identifiant du modèle.
     * @return Model|M|null Renvoie null lorsque le modèle n'existe pas.
     */
    public static function find(string $type, int $id): ?Model
    {
        $class = self::getClassName($type);

        if(! in_array($class, self::models)) {
            return null;
        }

        /** @var Collection $collection */
        $collection = call_user_func([$class, 'find'], [$id]);

        return $collection->first();
    }

    /**
     * Liste tous les modèles d'un certain type.
     * @param string|array|null $types Type des modèles à obtenir. Null pour obtenir tous les modèles de
     *                                 tous les types.
     * @param string|null       $search Termes de recherche. Null pour donner tous les résultats.
     * @return Collection<int, Model|M>|Model[]|M[]
     */
    public static function list($types = null, ?string $search = null): Collection
    {
        $result = collect();

        $classes = [];
        if($types === null) {
            $classes = self::models;
        } else {
            if(! is_array($types)) {
                $types = [$types];
            }

            foreach($types as $type) {
                $class = self::getClassName($type);
                if(! in_array($class, self::models)) {
                    continue;
                }
                $classes[] = $class;
            }
        }

        foreach($classes as $class) {
            /** @var Builder $query */
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
