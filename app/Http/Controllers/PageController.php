<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    /**
     * @param int $page
     * @param string $url
     * @return RedirectResponse|View
     */
    public function index(int $page, string $url)
    {
        $page = Page::findOrFail($page)->getPageOrFail();

        if(trim($page->url) !== trim($url)) {
            return redirect("page/{$page->id}-{$page->url}");
        }

        return view('page.show')->with('page', $page);
    }
}
