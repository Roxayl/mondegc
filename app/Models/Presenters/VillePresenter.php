<?php

namespace App\Models\Presenters;

trait VillePresenter
{
    public function accessorUrl() : string
    {
        $infrastructurableData = $this->getInfrastructurableData();
        return url("page-ville.php?ch_ville_id=" .
            $infrastructurableData['infrastructurable_id']);
    }

    public function backAccessorUrl() : string
    {
        $infrastructurableData = $this->getInfrastructurableData();
        return url("back/ville_modifier.php?ville-ID=" .
            $infrastructurableData['infrastructurable_id']);
    }

    public function getFlag() : string
    {
        return (string)$this->ch_vil_armoiries;
    }

    public function getName() : string
    {
        return $this->ch_vil_nom;
    }
}