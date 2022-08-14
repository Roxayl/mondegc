<?php

namespace App\Models\Traits;

use App\Models\ChapterResourceable;
use App\Services\EconomyService;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use YlsIdeas\FeatureFlags\Facades\Features;

trait Roleplayable
{
    /**
     * @return MorphMany
     */
    public function chapterResources(): MorphMany
    {
        return $this->morphMany(ChapterResourceable::class, 'resourceable');
    }

    /**
     * @return array<string, float>
     */
    public function roleplayResources(): array
    {
        if(! Features::accessible('roleplay')) {
            return EconomyService::resourcesPrefilled();
        }

        return EconomyService::sumGeneratedResourcesFromInfluencables($this->chapterResources);
    }
}
