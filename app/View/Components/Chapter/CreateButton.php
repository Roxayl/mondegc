<?php

namespace Roxayl\MondeGC\View\Components\Chapter;

use Roxayl\MondeGC\Models\Roleplay;
use Roxayl\MondeGC\View\Components\BaseComponent;
use Illuminate\View\View;

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
    public function render(): View
    {
        return view('chapter.components.create-button');
    }
}
