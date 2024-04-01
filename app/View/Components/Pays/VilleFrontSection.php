<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Pays;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;
use Roxayl\MondeGC\View\Components\BaseComponent;
use YlsIdeas\FeatureFlags\Facades\Features;

class VilleFrontSection extends BaseComponent
{
    public function __construct(public Pays $pays)
    {
    }

    /**
     * @return Collection<int, Ville>
     */
    public function villesWithoutSubdivisions(): Collection
    {
        if ($this->usesSubdivisions()) {
            return $this->pays->villes()->whereNull('subdivision_id')->get();
        }
        return $this->pays->villes;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        if ($this->usesSubdivisions()) {
            return view('pays.components.ville-subdivision-list');
        }
        return view('pays.components.ville-list');
    }

    private function usesSubdivisions(): bool
    {
        return Features::accessible('subdivision') && $this->pays->use_subdivisions;
    }
}
