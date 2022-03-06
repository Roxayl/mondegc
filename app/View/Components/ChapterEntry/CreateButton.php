<?php

namespace App\View\Components\ChapterEntry;

use App\Models\Chapter;
use App\View\Components\BaseComponent;
use Illuminate\View\View;

class CreateButton extends BaseComponent
{
    public Chapter $chapter;

    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter-entry.components.create-button');
    }
}
