<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Traits;

use Roxayl\MondeGC\Models\Influence;

trait DeletesInfluences
{
    /**
     * Supprime les influences de modèles liées à cet influencable.
     */
    public function deleteInfluences(): void
    {
        $influences = Influence::where('influencable_type', Influence::getActualClassNameForMorph(get_class()))
            ->where('influencable_id', $this->{$this->primaryKey})->get();
        foreach ($influences as $influence) {
            $influence->delete();
        }
    }
}
