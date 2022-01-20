<?php

namespace App\Models\Repositories;

use App\Models\Organisation;
use App\Models\Pays;
use App\Models\Ville;
use Illuminate\Support\Collection;

/**
 * Cette classe permet de gérer des collections de modèles implémentant {@see \App\Models\Contracts\Resourceable}.
 */
class Resourceable
{
    private ?Collection $data = null;

    private static array $models = [
        Organisation::class,
        Pays::class,
        Ville::class,
    ];

    /**
     * @return array|string[]
     */
    public static function getModels(): array
    {
        return self::$models;
    }

    public function query(): self
    {
        $this->data = collect();

        return $this;
    }

    public function all(): self
    {
        $resourceables = collect();

        foreach (self::$models as $model) {
            $resourceables = $resourceables->merge($model::all());
        }

        $this->data = $resourceables;

        return $this;
    }

    public function withResources(): self
    {
        $this->data->map(function($item) {
            return $item->append('resources');
        });

        return $this;
    }

    public function get(): ?Collection
    {
        return $this->data;
    }
}
