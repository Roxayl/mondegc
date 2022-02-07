<?php

namespace App\View\Components\Blocks;

use App\View\Components\BaseComponent;
use Diff;
use Diff_Renderer_Html_SideBySide;
use DOMDocument;

class TextDiff extends BaseComponent
{
    public string $text1;
    public string $text2;

    public ?array $text1Structured = null;
    public ?array $text2Structured = null;

    protected ?Diff $diff = null;
    public array $options;
    protected string $renderer = Diff_Renderer_Html_SideBySide::class;

    public function __construct(string $text1, string $text2)
    {
        $this->text1 = $text1;
        $this->text2 = $text2;

        $this->options = [
            'ignoreWhitespace' => true,
            'ignoreCase' => false,
        ];
    }

    public function getText1Structured(): array
    {
        if($this->text1Structured === null) {
            $this->text1Structured = $this->formatHtml($this->text1);
        }

        return $this->text1Structured;
    }

    public function getText2Structured(): array
    {
        if($this->text2Structured === null) {
            $this->text2Structured = $this->formatHtml($this->text2);
        }

        return $this->text2Structured;
    }

    private function formatHtml(string $text): array
    {
        $dom = new DOMDocument();

        $dom->preserveWhiteSpace = false;
        $dom->loadHTML($text);
        $dom->formatOutput = true;

        $html = $dom->saveHTML();

        return explode("\n", $html);
    }

    public function getDiff(): Diff
    {
        if(is_null($this->diff)) {
            $this->diff = new Diff(
                $this->getText1Structured(),
                $this->getText2Structured(),
                $this->options
            );
        }

        return $this->diff;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->getDiff()->render(new ($this->renderer)());
    }
}
