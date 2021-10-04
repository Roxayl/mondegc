<?php

namespace App\View\Components\Roleplay;

use App\Models\Chapter as ChapterModel;
use Illuminate\View\Component;

class Chapter extends Component
{
    private ChapterModel $chapter;

    public function __construct(ChapterModel $chapter)
    {
        $this->chapter = $chapter;
    }

    public function render()
    {
        return view('roleplay.components.chapter', [
            'chapter' => $this->chapter,
        ]);
    }
}
