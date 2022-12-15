<?php

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\Models\ChapterEntry;
use Roxayl\MondeGC\View\Components\BaseComponent;

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
