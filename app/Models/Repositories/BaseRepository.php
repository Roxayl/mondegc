<?php

namespace App\Models\Repositories;

use Illuminate\Support\Collection;

/**
 * Classe abstraite représentant une collection de modèles ou d'entités.
 * @method self beforeGetting() Exécute une fonction avant d'appeler la méthode {@see get()}.
 */
abstract class BaseRepository
{
    /**
     * Nombre d'éléments par page, utilisé pour la pagination.
     */
    public const perPage = 10;

    /**
     * @var int|null Nombre d'éléments au total.
     */
    private ?int $totalCount;

    /**
     * @var Collection<int, ?>|null
     */
    protected ?Collection $collection = null;

    /**
     * @return $this
     */
    public function query(): self
    {
        $this->collection = collect();
        $this->totalCount = null;

        return $this;
    }

    /**
     * @return Collection<int, ?>
     */
    public function get(): ?Collection
    {
        if(method_exists($this, 'beforeGetting')) {
            $this->beforeGetting();
        }

        if($this->totalCount === null) {
            $this->totalCount = $this->collection->count();
        }

        return $this->collection;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function paginate(int $page): self
    {
        $this->totalCount = $this->collection->count();
        $this->collection = $this->collection->forPage($page, self::perPage);

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return $this
     */
    public abstract function all(): self;
}
