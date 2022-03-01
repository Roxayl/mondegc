<?php

namespace App\View\Components\ChapterEntry;

use Illuminate\View\View;

class ForumPost extends BaseMediaEntry
{
    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter-entry.media.forum-post', ['entry' => $this->entry]);
    }

    /**
     * @inheritDoc
     * @todo Terminer la génération des données.
     */
    public function generateData(array $parameters): array
    {
        return [
            'meta' => [
                'url' => 'https://www.forum-gc.com/t2501p100-shibubu-reviendra#295613',
            ],
            'media' => [
                'author' => 'skrimsli snjor',
                'text' => 'Je reviens au bon moment ^^ !!',
            ],
        ];
    }
}
