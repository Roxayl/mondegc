<?php

namespace App\View\Components\Roleplay;

use App\Models\Chapter as ChapterModel;
use App\View\Components\BaseComponent;
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
        return view('roleplay.components.chapter', [
            'chapter' => $this->chapter,
        ]);
    }
}
