<?php

namespace App\Models\Presenters;

trait PaysPresenter
{
    public function accessorUrl(): string
    {
        $infrastructurableData = $this->getInfrastructurableData();
        return url('page-pays.php?ch_pay_id=' .
            $infrastructurableData['infrastructurable_id']);
    }

    public function backAccessorUrl(): string
    {
        $infrastructurableData = $this->getInfrastructurableData();
        return url("back/page_pays_back.php?paysID=" .
            $infrastructurableData['infrastructurable_id']);
    }

    public function getFlag(): string
    {
        return (string)$this->ch_pay_lien_imgdrapeau;
    }

    public function getName(): string
    {
        return $this->ch_pay_nom;
    }
}
