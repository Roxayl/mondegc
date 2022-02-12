<?php

namespace App\View\Components\Blocks;

use App\View\Components\BaseComponent;
use Caxy\HtmlDiff\HtmlDiff;

class TextDiff extends BaseComponent
{
    public string $text1;
    public string $text2;

    public function __construct(string $text1, string $text2)
    {
        $this->text1 = $text1;
        $this->text2 = $text2;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return (new HtmlDiff($this->text1, $this->text2))->build();
    }
}
