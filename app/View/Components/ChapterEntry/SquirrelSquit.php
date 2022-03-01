<?php

namespace App\View\Components\ChapterEntry;

use Illuminate\View\View;

class SquirrelSquit extends BaseMediaEntry
{
    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('chapter-entry.media.squirrel-squit', ['entry' => $this->entry]);
    }

    /**
     * @inheritDoc
     * @todo Terminer la génération des données.
     */
    public function generateData(array $parameters): array
    {
        return [
            'meta' => [
                'url' => $parameters['url'],
            ],
            'media' => [
                'author' => 'Squirrel',
                'text' => 'Lorem ipsum...',
                'date' => '2020-12-12 00:00:00',
            ],
        ];
    }
}
