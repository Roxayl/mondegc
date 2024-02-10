<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\Models\ChapterEntry;
use Roxayl\MondeGC\View\Components\BaseComponent;

class CreateChapterEntry extends BaseComponent
{
    public ChapterEntry $entry;

    public function __construct(public Chapter $chapter)
    {
        $this->entry = new ChapterEntry();
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter-entry.create');
    }
}
