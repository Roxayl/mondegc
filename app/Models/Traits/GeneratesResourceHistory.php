<?php

namespace App\Models\Traits;

use App\Models\Organisation;
use App\Models\Pays;
use App\Models\ResourceHistory;
use App\Models\Ville;

trait GeneratesResourceHistory
{
    /**
     * Créé une entrée dans l'historique des ressources d'un modèle ressourçable.
     *
     * @return ResourceHistory
     */
    public function generateResourceHistory(): ResourceHistory
    {
        /** @var Pays|Ville|Organisation|\App\Models\Contracts\Resourceable $this */
        $resources = $this->resources();

        $fields = array_merge([
            'resourceable_id'   => $this->getKey(),
            'resourceable_type' => get_class($this),
        ], $resources);

        return ResourceHistory::create($fields);
    }
}
