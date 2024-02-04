<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Presenters;

use Illuminate\Support\Str;

trait SubdivisionPresenter
{
    public function showRouteParameter(): array
    {
        return [
            'paysId' => $this->pays?->ch_pay_id,
            'paysSlug' => Str::slug($this->pays?->ch_pay_nom),
            'subdivisionTypeName' => Str::slug($this->subdivisionType?->type_name),
            'subdivision' => $this,
            'subdivisionSlug' => Str::slug($this->name),
        ];
    }
}
