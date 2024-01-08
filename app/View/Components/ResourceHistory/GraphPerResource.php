<?php

namespace Roxayl\MondeGC\View\Components\ResourceHistory;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\ResourceHistory;

class GraphPerResource extends Graph
{
    /**
     * @var Collection<int, Resourceable>
     */
    public Collection $resourceables;

    /**
     * @var string
     */
    public string $resourceName;

    public ?Carbon $startDate;

    public ?Carbon $endDate;

    /**
     * @param Collection $resourceables
     * @param string $resourceName
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     */
    public function __construct(
        Collection $resourceables,
        string $resourceName,
        Carbon $startDate = null,
        Carbon $endDate = null
    ) {
        $this->resourceables = $resourceables;
        $this->resourceName = $resourceName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

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
     * @return View
     */
    public function render(): View
    {
        return view('blocks.resource-graph', [
            'chartData'    => $this->chartData,
            'resourceName' => $this->resourceName,
            'graphId'      => $this->graphId,
        ]);
    }

    /**
     * @return array
     */
    private function generateDatasets(): array
    {
        $datasets = [];

        foreach($this->resourceables as $resourceable) {
            $dataset = [
                'label' => Str::limit($resourceable->getName(), 25),
                'data' => [],
                'fill' => false,
                'borderColor' => '#' . $this->generateColorHex()
            ];

            $resourceableEntries = ResourceHistory::forResourceable($resourceable)
                ->chartSelect($this->startDate, $this->endDate)
                ->get();

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
