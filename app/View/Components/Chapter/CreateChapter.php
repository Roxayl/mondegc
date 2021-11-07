<?php

namespace App\View\Components\Chapter;

use App\Models\Chapter;
use App\Models\Roleplay;
use App\View\Components\BaseComponent;

class CreateChapter extends BaseComponent
{
    public Roleplay $roleplay;
    public Chapter $chapter;

    public function __construct(Roleplay $roleplay)
    {
        $this->roleplay = $roleplay;
        $this->chapter = new Chapter();
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return view('chapter.components.create');
    }
}
