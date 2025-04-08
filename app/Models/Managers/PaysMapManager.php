<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Managers;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Enums\Resource;
use Roxayl\MondeGC\Models\Influence;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Traits\Influencable as GeneratesInfluence;

class PaysMapManager implements Influencable
{
    use GeneratesInfluence;

    protected Pays $pays;

    public function __construct(Pays $pays)
    {
        $this->pays = $pays;
    }

    public function influences(): MorphMany
    {
        // On écrase la méthode influence() définie dans le trait Influencable
        // car on retourne la relation liée à $this->pays, pas $this.
        return $this->pays->morphMany(Influence::class, 'influencable');
    }

    /**
     * Génère un tableau contenant les ressources de la carte.
     *
     * @return array
     */
    public function mapResources(): array
    {
        $result = [];
        foreach (Resource::cases() as $resource) {
            $field = "ch_pay_{$resource->value}_carte";
            $result[$resource->value] = $this->pays->$field;
        }

        return $result;
    }

    public function removeOldInfluenceRows(?\Closure $f = null): bool
    {
        // On écrase la méthode définie dans le trait, car elle recherche une relation
        // dans Models\Pays... et comme PaysMapManager n'est pas une classe Eloquent...
        // on est obligé de rechercher les influences du pays "manuellement".

        $influencable_type = Influence::getActualClassNameForMorph(get_class());

        $existingInfluences = Influence::where('influencable_id', $this->pays->ch_pay_id)
            ->where('influencable_type', $influencable_type)->get();

        foreach ($existingInfluences as $existingInfluence) {
            $existingInfluence->delete();
        }

        return true;
    }

    public function generateInfluence(): void
    {
        $influencable_type = Influence::getActualClassNameForMorph(get_class());

        $this->removeOldInfluenceRows();

        $resources = $this->mapResources();

        $influence = new Influence;
        $influence->influencable_type = $influencable_type;
        $influence->influencable_id = $this->pays->ch_pay_id;
        $influence->generates_influence_at = $this->pays->ch_pay_date;
        $influence->fill($resources)
            ->save();
    }

    public function isEnabled(): bool
    {
        return $this->pays->isEnabled();
    }
}
