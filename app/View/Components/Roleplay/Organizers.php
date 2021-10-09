<?php

namespace App\View\Components\Roleplay;

use App\Models\Roleplay;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Organizers extends Component
{
    private Roleplay $roleplay;

    public function __construct(Roleplay $roleplay)
    {
        $this->roleplay = $roleplay;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('roleplay.components.organizers', [
            'roleplay' => $this->roleplay,
        ]);
    }
}
