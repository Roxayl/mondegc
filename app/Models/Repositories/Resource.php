<?php

namespace Roxayl\MondeGC\Models\Repositories;

use Roxayl\MondeGC\Models\Contracts\Resourceable as IResourceable;
use Roxayl\MondeGC\Models\Factories\ResourceableFactory;
use Illuminate\Support\Collection;

/**
 * Cette classe permet de gérer des collections de modèles de ressources.
 */
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
            ]));
        });

        $this->collection = $resources;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function beforeGetting(): self
    {
        $this->collection->map(/**
         * @param Collection<int, Collection>|Collection[] $resources
         * @return Collection<int, Collection>|Collection[]
         */ function($resources) {
            /** @var IResourceable $resourceable */
            $resourceable = ResourceableFactory::find(
                $resources->get('model_type'),
                $resources->get('model_id'));
            $resources->put('resources', $resourceable->resources());
            return $resources;
        });

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
