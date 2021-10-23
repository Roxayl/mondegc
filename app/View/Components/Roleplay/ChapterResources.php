<?php

namespace App\View\Components\Roleplay;

use App\Models\Chapter;
use App\View\Components\BaseComponent;
use Illuminate\Contracts\View\View;

class ChapterResources extends BaseComponent
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
        return view('roleplay.components.chapter-resources');
    }
}
