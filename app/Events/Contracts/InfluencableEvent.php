<?php

namespace Roxayl\MondeGC\Events\Contracts;

use Roxayl\MondeGC\Models\Contracts\Influencable;

interface InfluencableEvent
{
    /**
     * Donne l'influençable (e.g. Ville, Pays, Organisation) lié à l'infrastructure qui fait l'objet d'un événement.
     *
     * @return Influencable
     */
    public function getInfluencable(): Influencable;
}
