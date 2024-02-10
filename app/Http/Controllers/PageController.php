<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Roxayl\MondeGC\Models\Page;

class PageController extends Controller
{
    /**
     * @param int $page
     * @param string $url
     * @return RedirectResponse|View
     */
    public function index(int $page, string $url): RedirectResponse|View
    {
        $page = Page::query()->findOrFail($page)->getPageOrFail();

        if(trim($page->url) !== trim($url)) {
            return redirect("page/$page->id-$page->url");
        }

        return view('page.show')->with('page', $page);
    }
}
