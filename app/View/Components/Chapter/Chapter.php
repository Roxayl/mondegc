<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Chapter;

use Illuminate\Contracts\View\View;
use Roxayl\MondeGC\Models\Chapter as ChapterModel;
use Roxayl\MondeGC\View\Components\BaseComponent;

class Chapter extends BaseComponent
{
    public function __construct(private readonly ChapterModel $chapter)
    {
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
