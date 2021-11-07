<?php

namespace App\View\Components\Chapter;

use App\Models\Roleplay;
use App\View\Components\BaseComponent;

class CreateButton extends BaseComponent
{
    public Roleplay $roleplay;

    public function __construct(Roleplay $roleplay)
    {
        $this->roleplay = $roleplay;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return view('chapter.components.create-button');
    }
}
