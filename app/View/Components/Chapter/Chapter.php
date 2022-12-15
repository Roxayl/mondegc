<?php

namespace Roxayl\MondeGC\View\Components\Chapter;

use Roxayl\MondeGC\Models\Chapter as ChapterModel;
use Roxayl\MondeGC\View\Components\BaseComponent;
use Illuminate\Contracts\View\View;

class Chapter extends BaseComponent
{
    private ChapterModel $chapter;

    public function __construct(ChapterModel $chapter)
    {
        $this->chapter = $chapter;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter.show', [
            'chapter' => $this->chapter,
        ]);
    }
}
