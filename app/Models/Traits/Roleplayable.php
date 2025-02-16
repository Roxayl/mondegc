<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Roxayl\MondeGC\Models\ChapterResourceable;
use Roxayl\MondeGC\Services\Gameplay\EconomyService;
use YlsIdeas\FeatureFlags\Facades\Features;

trait Roleplayable
{
    /**
     * @return MorphMany
     */
    public function chapterResources(): MorphMany
    {
        return $this->morphMany(ChapterResourceable::class, 'resourceable')
            ->selectRaw(DB::raw('DISTINCT chapter_resourceable.id')->getValue(DB::connection()->getQueryGrammar()))
            ->join('chapters', 'chapters.id', '=', 'chapter_resourceable.chapter_id')
            ->join('roleplay', 'roleplay.id', '=', 'chapters.roleplay_id')
            ->where('roleplay.deleted_at', null)
            ->where('chapters.deleted_at', null);
    }

    /**
     * @return array<string, float>
     */
    public function roleplayResources(): array
    {
        if (! Features::accessible('roleplay')) {
            return EconomyService::resourcesPrefilled();
        }

        return EconomyService::sumGeneratedResourcesFromInfluencables($this->chapterResources);
    }
}
