<?php

namespace App\View\Components\Roleplay;

use App\Models\Roleplay;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class IndexList extends Component
{
    private Collection $rps;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rps = Roleplay::current()->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('roleplay.components.index-list', [
            'rps' => $this->rps
        ]);
    }
}
