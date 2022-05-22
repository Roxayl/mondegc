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
        $sumResources = EconomyService::resourcesPrefilled();

        if(! Features::accessible('roleplay')) {
            return $sumResources;
        }

        foreach($this->chapterResources as $chapterResource) {
            $generatedResources = $chapterResource->getGeneratedResources();
            foreach(config('enums.resources') as $resource) {
                $sumResources[$resource] = $generatedResources[$resource];
            }
        }

        return $sumResources;
    }
}
