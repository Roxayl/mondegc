<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\ChapterEntry;

use GuzzleHttp\Client;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Response;
use Illuminate\View\View;
use simplehtmldom\HtmlDocument;

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
     */
    public function generateData(array $parameters): array
    {
        $url = $parameters['url'];
        $postId = parse_url($url)['fragment'];
        if(! is_numeric($postId)) {
            throw new \InvalidArgumentException("L'identifiant du post n'est pas correct.");
        }

        $client = new Client();
        $response = $client->request('GET', $url);
        /** @noinspection PhpClassConstantAccessedViaChildClassInspection */
        if($response->getStatusCode() !== Response::HTTP_OK) {
            throw new HttpClientException("Impossible de charger le post du forum.");
        }

        $html = new HtmlDocument($response->getBody()->getContents());
        $author = trim(strip_tags($html->find('div#profile' . $postId, 0)
                                       ->find('.postprofile-name', 0)->innertext));
        $text = trim($html->find('div#p' . $postId, 0)->find('.content', 0)->innertext);
        $text = str_replace('src="/', 'src="https://www.forum-gc.com/', $text);

        return [
            'meta' => [
                'url' => $url,
            ],
            'media' => [
                'author' => $author,
                'text' => $text,
            ],
        ];
    }
}
