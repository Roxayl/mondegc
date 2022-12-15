<?php

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use Illuminate\View\View;
use Roxayl\MondeGC\Models\Communique;

class MondeCommunique extends BaseMediaEntry
{
    public ?Communique $communique = null;

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        $data = $this->entry->media_data;

        $this->communique = Communique::find($data['media']['communique_id']);

        return view('chapter-entry.media.monde-communique', [
            'entry' => $this->entry,
            'communique' => $this->communique,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function generateData(array $parameters): array
    {
        $url = $parameters['url'];

        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'], $queryString);

        return [
            'meta' => [
                'url' => $parameters['url'],
            ],
            'media' => [
                'communique_id' => $queryString['com_id'],
            ],
        ];
    }
}
