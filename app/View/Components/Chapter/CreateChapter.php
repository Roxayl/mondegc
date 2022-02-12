<?php

namespace App\View\Components\Chapter;

use App\Models\Chapter;
use App\Models\Roleplay;
use App\View\Components\BaseComponent;
use Illuminate\View\View;

class CreateChapter extends BaseComponent
{
    public Chapter $chapter;

    public Roleplay $roleplay;

    public function __construct(Roleplay $roleplay)
    {
        $this->chapter = new Chapter();
        $this->roleplay = $roleplay;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter.components.create');
    }
}
