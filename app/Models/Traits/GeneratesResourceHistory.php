<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Traits;

use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\ResourceHistory;
use Roxayl\MondeGC\Models\Ville;

trait GeneratesResourceHistory
{
    /**
     * Créé une entrée dans l'historique des ressources d'un modèle ressourçable.
     *
     * @return ResourceHistory
     */
    public function generateResourceHistory(): ResourceHistory
    {
        /** @var (Pays|Ville|Organisation)&Resourceable $this */
        $resources = $this->resources();

        $fields = array_merge([
            'resourceable_id' => $this->getKey(),
            'resourceable_type' => get_class($this),
        ], $resources);

        return ResourceHistory::create($fields);
    }
}
