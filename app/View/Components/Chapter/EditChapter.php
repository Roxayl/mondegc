<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Chapter;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\Models\Roleplay;
use Roxayl\MondeGC\View\Components\BaseComponent;

class EditChapter extends BaseComponent
{
    public Roleplay $roleplay;

    public function __construct(public Chapter $chapter)
    {
        $this->roleplay = $chapter->roleplay;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter.components.edit');
    }
}
