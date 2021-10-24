<?php

namespace App\Models\Presenters;

trait OrganisationPresenter
{
    public function accessorUrl(): string
    {
        return route('organisation.showslug', $this->showRouteParameter());
    }

    public function backAccessorUrl(): string
    {
        return route('organisation.edit', ['organisation' => $this->id]);
    }

    public function getFlag(): string
    {
        return (string)$this->flag;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function showRouteParameter(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
        ];
    }
}
