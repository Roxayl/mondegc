<?php

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\Models\ChapterEntry;
use Roxayl\MondeGC\View\Components\BaseComponent;
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
