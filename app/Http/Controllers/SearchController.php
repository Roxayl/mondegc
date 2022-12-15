<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Patrimoine;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    /**
     * Renvoie la page de l'outil de recherche.
     *
     * @param Request $request
     * @return View
     */
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
