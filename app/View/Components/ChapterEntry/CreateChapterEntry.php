<?php

namespace App\View\Components\ChapterEntry;

use App\Models\Chapter;
use App\Models\ChapterEntry;
use App\View\Components\BaseComponent;
use Illuminate\View\View;

class CreateChapterEntry extends BaseComponent
{
    public ChapterEntry $entry;

    public Chapter $chapter;

    public function __construct(Chapter $chapter)
    {
        $this->entry = new ChapterEntry();
        $this->chapter = $chapter;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter-entry.create');
    }
}
