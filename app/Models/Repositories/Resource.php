<?php

namespace App\Models\Repositories;

use App\Models\Contracts\Resourceable as IResourceable;
use Illuminate\Support\Collection;

class Resource extends Resourceable
{
    /**
     * @param array|null $models
     * @return $this
     */
    public function fetch(?array $models = null): self
    {
        if($models === null) {
            $models = self::$models;
        }

        /** @var Collection<int, IResourceable>|IResourceable[] $resourceables */
        $resourceables = (new Resourceable)->query()->fetch($models)->get();

        $resources = collect();
        $resourceables->map(function($resourceable) use ($resources) {
            $resources->add(collect([
                'model_type' => get_class($resourceable),
                'model_id'   => $resourceable->getKey(),
                'name'       => $resourceable->getName(),
                'ressources' => $resourceable->resources(),
            ]));
        });

        $this->collection = $resources;

        return $this;
    }

    /**
     * @return $this
     */
    public function all(): self
    {
        return $this->fetch();
    }
}
