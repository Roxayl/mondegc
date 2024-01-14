<?php

namespace Roxayl\MondeGC\View\Components\Blocks;

use Caxy\HtmlDiff\HtmlDiff;
use Caxy\HtmlDiff\HtmlDiffConfig;
use Roxayl\MondeGC\View\Components\BaseComponent;

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
        $config = new HtmlDiffConfig();

        return (new HtmlDiff($this->text1, $this->text2))
            ->setConfig($config)
            ->build();
    }
}
