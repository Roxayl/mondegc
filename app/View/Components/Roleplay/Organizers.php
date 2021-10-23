<?php

namespace App\View\Components\Roleplay;

use App\Models\Roleplay;
use App\View\Components\BaseComponent;
use Illuminate\Contracts\View\View;

class Organizers extends BaseComponent
{
    public Roleplay $roleplay;

    public function __construct(Roleplay $roleplay)
    {
        $this->roleplay = $roleplay;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('roleplay.components.organizers');
    }
}
