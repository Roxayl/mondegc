<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Chapter;

use Illuminate\Contracts\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\View\Components\BaseComponent;

class ChapterResources extends BaseComponent
{
    public function __construct(public Chapter $chapter)
    {
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter.components.resources');
    }
}
