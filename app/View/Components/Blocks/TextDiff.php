<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Blocks;

use Caxy\HtmlDiff\HtmlDiff;
use Caxy\HtmlDiff\HtmlDiffConfig;
use Roxayl\MondeGC\View\Components\BaseComponent;

class TextDiff extends BaseComponent
{
    public function __construct(public string $text1, public string $text2)
    {
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
