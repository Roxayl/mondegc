<?php

namespace App\Models\Traits;

use App\Models\Influence;

trait DeletesInfluences
{
    /**
     * Supprime les influences de modèles liées à cet influencable.
     */
    public function deleteInfluences(): void
    {
        $influences = Influence
            ::where('influencable_type', Influence::getActualClassNameForMorph(get_class()))
            ->where('influencable_id', $this->{$this->primaryKey})->get();
        foreach($influences as $influence) {
            $influence->delete();
        }
    }
}
