<?php

namespace Roxayl\MondeGC\View\Components\Chapter;

use Illuminate\Contracts\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\View\Components\BaseComponent;

class ChapterEntries extends BaseComponent
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
        return view('chapter.components.entries');
    }
}
