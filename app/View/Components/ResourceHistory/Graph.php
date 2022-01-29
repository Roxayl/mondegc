<?php

namespace App\View\Components\ResourceHistory;

use Illuminate\View\Component;
use Illuminate\View\View;

abstract class Graph extends Component
{
    /**
     * @var array|array[]
     */
    public array $chartData;

    /**
     * @return View
     */
    public function render(): View
    {
        return view('blocks.resource-graph', [
            'chartData' => $this->chartData
        ]);
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
