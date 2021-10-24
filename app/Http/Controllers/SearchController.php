<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\Pays;
use App\Models\Ville;
use App\Models\Patrimoine;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('query', '');
        if(empty($query)) {
            return view('search.search');
        }

        $results = (new Search())
            ->registerModel(Pays::class, ['ch_pay_nom'])
            ->registerModel(Organisation::class, ['name'])
            ->registerModel(Ville::class, ['ch_vil_nom'])
            ->registerModel(Patrimoine::class, ['ch_pat_nom'])
            ->search($query);

        return view('search.search', compact(['query', 'results']));
    }
}
