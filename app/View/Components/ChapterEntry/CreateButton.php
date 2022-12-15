<?php

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\View\Components\BaseComponent;

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
