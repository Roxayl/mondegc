<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Repositories;

use Illuminate\Database\Eloquent\Model;
use Roxayl\MondeGC\Models\Contracts\Resourceable as IResourceable;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;

/**
 * Cette classe permet de gérer des collections de modèles implémentant {@see IResourceable}.
 */
class Resourceable extends BaseRepository
{
    /**
     * @var array|string[]
     */
    protected static array $models = [
        'organisation' => Organisation::class,
        'pays' => Pays::class,
        'ville' => Ville::class,
    ];

    /**
     * @return array|string[]
     */
    public static function getModels(): array
    {
        return self::$models;
    }

    /**
     * @return $this
     */
    public function all(): self
    {
        return $this->fetch();
    }

    /**
     * @param  array|null  $models
     * @return $this
     */
    public function fetch(?array $models = null): self
    {
        $resourceables = collect();

        if ($models === null) {
            $models = self::$models;
        }

        foreach ($models as $model) {
            $resourceables = $resourceables->merge($model::visible()->get());
        }

        $this->collection = $resourceables;

        return $this;
    }

    /**
     * @return $this
     */
    public function pays(): self
    {
        return $this->fetch([Pays::class]);
    }

    /**
     * @return $this
     */
    public function ville(): self
    {
        return $this->fetch([Ville::class]);
    }

    /**
     * @return $this
     */
    public function organisation(): self
    {
        return $this->fetch([Organisation::class]);
    }

    /**
     * @return $this
     */
    public function withResources(): self
    {
        $this->collection->map(/**
         * @param  IResourceable|Model  $resourceable
         * @return IResourceable
         */ function (IResourceable $resourceable) {
            if (get_class($resourceable) === Ville::class) {
                return $resourceable;
            }

            return $resourceable->append('resources');
        });

        return $this;
    }
}
