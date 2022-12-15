<?php

namespace Roxayl\MondeGC\View\Components\Chapter;

use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\Models\Roleplay;
use Roxayl\MondeGC\View\Components\BaseComponent;
use Illuminate\View\View;

class CreateChapter extends BaseComponent
{
    public Chapter $chapter;

    public Roleplay $roleplay;

    public function __construct(Roleplay $roleplay)
    {
        $this->chapter = new Chapter();
        $this->roleplay = $roleplay;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter.components.create');
    }
}
