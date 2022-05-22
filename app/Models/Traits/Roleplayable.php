<?php

namespace App\Models\Traits;

use App\Models\ChapterResourceable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Roleplayable
{
    /**
     * @return MorphMany
     */
    public function chapterResources(): MorphMany
    {
        return $this->morphMany(ChapterResourceable::class, 'resourceable');
    }
}
