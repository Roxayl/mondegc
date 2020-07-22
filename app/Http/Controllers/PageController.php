<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller {

    public function index($page, $url) {

        $thisPage = Page::findOrFail($page)->getPageOrFail();

        if(trim($thisPage->url) !== trim($url)) {
            return redirect("page/{$thisPage->id}-{$thisPage->url}");
        }

        $page_title = $thisPage->title;
        $seo_description = $thisPage->seodescription;
        $content = $thisPage->content;
        $title = $page_title;

        return view('page', compact(
            ['content', 'title', 'page_title', 'seo_description']));

    }

}
