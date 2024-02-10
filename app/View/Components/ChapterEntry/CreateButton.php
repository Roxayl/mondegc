<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\View\Components\BaseComponent;

class CreateButton extends BaseComponent
{
    public function __construct(public Chapter $chapter)
    {
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter-entry.components.create-button');
    }
}
