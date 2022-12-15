<?php

namespace Roxayl\MondeGC\View\Components\Blocks;

use Roxayl\MondeGC\Services\EconomyService;
use Illuminate\View\Component;
use Illuminate\View\View;

class ResourceSelector extends Component
{
    public array $resourceList;

    public int $leftColumnCount;

    public int $rightColumnCount;

    public string $uniqid;

    public array $oldValues;

    public function __construct(?array $oldValues = null)
    {
        $this->resourceList = config('enums.resources');

        $nbResources = count($this->resourceList);
        $this->leftColumnCount = $this->rightColumnCount = $nbResources / 2;
        if(count($this->resourceList) % 2 !== 0) {
            $this->leftColumnCount++;
        }

        if($oldValues === null) {
            $this->oldValues = EconomyService::resourcesPrefilled();
        } else {
            $this->oldValues = $oldValues;
        }

        $this->uniqid = uniqid();
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('blocks.resource-selector');
    }
}
