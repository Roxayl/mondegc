<?php

namespace Roxayl\MondeGC\View\Components\Roleplay;

use Roxayl\MondeGC\Models\Roleplay;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class IndexList extends Component
{
    private Collection $rps;

    public function __construct()
    {
        $this->rps = Roleplay::current()->get();
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('roleplay.components.index-list', [
            'rps' => $this->rps
        ]);
    }
}
