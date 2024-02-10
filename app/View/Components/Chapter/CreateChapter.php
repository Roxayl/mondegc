<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Chapter;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\Models\Roleplay;
use Roxayl\MondeGC\View\Components\BaseComponent;

class CreateChapter extends BaseComponent
{
    public Chapter $chapter;

    public function __construct(public Roleplay $roleplay)
    {
        $this->chapter = new Chapter();
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter.components.create');
    }
}
