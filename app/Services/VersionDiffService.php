<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Roxayl\MondeGC\Models\Traits\Versionable;
use Roxayl\MondeGC\View\Components\Blocks\TextDiff;

class VersionDiffService
{
    /**
     * @param Model&Versionable $model1
     * @param Model&Versionable $model2
     * @param array<string> $fields
     * @return Collection<string, string>
     */
    public function generate(Model $model1, Model $model2, array $fields = null): Collection
    {
        $diffs = collect();

        if($fields === null) {
            $fields = array_diff($model1->getFillable(), $model1->getDontVersionFields());
        }

        foreach($fields as $field) {
            if($model1->$field === $model2->$field) {
                continue;
            }
            $diffs->put($field, (new TextDiff((string) $model2->$field, (string) $model1->$field))->render());
        }

        return $diffs;
    }
}
