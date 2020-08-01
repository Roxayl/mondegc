<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller {

    public function index($page, $url) {

        $page = Page::findOrFail($page)->getPageOrFail();

        if(trim($page->url) !== trim($url)) {
            return redirect("page/{$page->id}-{$page->url}");
        }

        return view('page')->with('page', $page);

    }

}
