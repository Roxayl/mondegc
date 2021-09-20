<?php

namespace App\View\Components\Blocks;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ScriptConfiguration extends Component
{
    private array $data;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(defined('DEF_URI_PATH')) {
            $baseUrl = DEF_URI_PATH;
        } else {
            $baseUrl = url('');
        }

        if(! Str::endsWith($baseUrl, '/')) {
            $baseUrl .= '/';
        }

        $this->data = [
            'base_url' => $baseUrl,
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.blocks.script-configuration', [
            'data' => $this->data,
        ]);
    }
}
