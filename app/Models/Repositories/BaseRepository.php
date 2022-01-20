<?php

namespace App\Models\Repositories;

use Illuminate\Support\Collection;

abstract class BaseRepository
{
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

        return $this;
    }

    /**
     * @return Collection<int, ?>
     */
    public function get(): ?Collection
    {
        return $this->collection;
    }

    /**
     * @return $this
     */
    public abstract function all(): self;
}
