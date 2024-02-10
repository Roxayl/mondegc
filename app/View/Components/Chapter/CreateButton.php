<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Chapter;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Roleplay;
use Roxayl\MondeGC\View\Components\BaseComponent;

class CreateButton extends BaseComponent
{
    public function __construct(public Roleplay $roleplay)
    {
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter.components.create-button');
    }
}
