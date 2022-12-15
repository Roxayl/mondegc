<?php

namespace Roxayl\MondeGC\View\Components\Chapter;

use Illuminate\Contracts\View\View;
use Roxayl\MondeGC\Models\Chapter as ChapterModel;
use Roxayl\MondeGC\View\Components\BaseComponent;

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
