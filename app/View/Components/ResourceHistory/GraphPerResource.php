<?php

namespace App\View\Components\ResourceHistory;

use App\Models\Contracts\Resourceable;
use App\Models\ResourceHistory;
use Illuminate\Support\Collection;

class GraphPerResource extends Graph
{
    /**
     * @var Collection<int, Resourceable>|Resourceable[]
     */
    public Collection $resourceables;

    /**
     * @var string
     */
    public string $resourceName;

    public function __construct(Collection $resourceables, string $resourceName)
    {
        $this->resourceables = $resourceables;
        $this->resourceName = $resourceName;
        $this->chartData = $this->generateChartData();
    }

    /**
     * @return array
     */
    public function generateChartData(): array
    {
        return [
            'datasets' => $this->generateDatasets(),
        ];
    }

    /**
     * @return array
     */
    private function generateDatasets(): array
    {
        $datasets = [];

        foreach($this->resourceables as $resourceable) {
            $dataset = [
                'label' => $this->resourceName . ' de ' . $resourceable->getName(),
                'data' => [],
                'fill' => false,
                'borderColor' => '#' . $this->generateColorHex()
            ];

            $resourceableEntries = ResourceHistory::forResourceable($resourceable)->chartSelect()->get();

            /** @var ResourceHistory $entry */
            foreach($resourceableEntries as $entry) {
                $dataset['data'][] = [
                    'x' => $entry->created_date,
                    'y' => $entry->{$this->resourceName},
                ];
            }

            $datasets[] = $dataset;
        }

        return $datasets;
    }
}
