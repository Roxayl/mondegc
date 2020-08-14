<?php

namespace App\Models\Presenters;

trait OrganisationPresenter
{
    public function showRouteParameter() : array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
        ];
    }
}