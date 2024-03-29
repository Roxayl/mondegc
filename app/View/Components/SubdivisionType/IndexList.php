<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\SubdivisionType;

use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\View\Components\BaseComponent;

class IndexList extends BaseComponent
{
    public function __construct(public Pays $pays)
    {
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return view('subdivision-type.components.list');
    }
}
