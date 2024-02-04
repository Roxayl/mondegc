<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Blocks;

use Illuminate\View\Component;
use Illuminate\View\View;
use Roxayl\MondeGC\Models\Enums\Resource;
use Roxayl\MondeGC\Services\EconomyService;

class ResourceSelector extends Component
{
    public array $resourceList;

    public int $leftColumnCount;

    public int $rightColumnCount;

    public string $uniqid;

    public array $oldValues;

    public function __construct(?array $oldValues = null)
    {
        $this->resourceList = array_column(Resource::cases(), 'value');

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
