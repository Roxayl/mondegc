<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Blocks;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ScriptConfiguration extends Component
{
    private array $data;

    public function __construct()
    {
        if (defined('DEF_URI_PATH')) {
            $baseUrl = DEF_URI_PATH;
        } else {
            $baseUrl = url('');
        }

        if (! Str::endsWith($baseUrl, '/')) {
            $baseUrl .= '/';
        }

        $this->data = [
            'base_url' => $baseUrl,
        ];
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('blocks.script-configuration', [
            'data' => $this->data,
        ]);
    }
}
