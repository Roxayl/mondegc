<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\ResourceHistory;

use Illuminate\View\Component;
use Illuminate\View\View;

abstract class Graph extends Component
{
    /**
     * @var array|array[]
     */
    public array $chartData;

    /**
     * @var string
     */
    public string $graphId = 'line-resource-chart';

    /**
     * @return View
     */
    public function render(): View
    {
        return view('blocks.resource-graph', [
            'chartData' => $this->chartData,
            'graphId' => $this->graphId,
        ]);
    }

    /**
     * @param  string  $id
     * @return $this
     */
    public function setGraphId(string $id): self
    {
        $this->graphId = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function generateColorHex(): string
    {
        $part = function () {
            return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
        };

        return $part() . $part() . $part();
    }
}
