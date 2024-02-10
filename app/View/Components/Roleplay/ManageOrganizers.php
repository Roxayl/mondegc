<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Roleplay;

use Illuminate\Contracts\View\View;
use Roxayl\MondeGC\Models\Roleplay;
use Roxayl\MondeGC\View\Components\BaseComponent;

class ManageOrganizers extends BaseComponent
{
    public function __construct(public Roleplay $roleplay)
    {
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('roleplay.components.manage-organizers');
    }
}
