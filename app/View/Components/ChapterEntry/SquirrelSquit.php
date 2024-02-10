<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use GuzzleHttp\Client;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Response;
use Illuminate\View\View;
use simplehtmldom\HtmlDocument;

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
     */
    public function generateData(array $parameters): array
    {
        $url = $parameters['url'];

        $client = new Client();
        $response = $client->request('GET', $url);
        /** @noinspection PhpClassConstantAccessedViaChildClassInspection */
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new HttpClientException('Impossible de charger le squit.');
        }

        $html = new HtmlDocument($response->getBody());
        $author = $html->find('meta[name="author"]', 0)->getAttribute('content');
        $text = $html->find('span.perm_squit_squit', 0)->innertext;
        $date = $html->find('meta[name="date"]', 0)->getAttribute('content');

        return [
            'meta' => [
                'url' => $url,
            ],
            'media' => [
                'author' => $author,
                'text' => $text,
                'date' => $date,
            ],
        ];
    }
}
