<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\Pays;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function index(Request $request) {

        $query = Input::get('query', '');
        if(empty($query)) {
            return view('search.search');
        }

        $results = (new Search())
            ->registerModel(Pays::class, ['ch_pay_nom'])
            ->registerModel(Organisation::class, ['name'])
            ->registerModel(Ville::class, ['ch_vil_nom'])
            ->search($query);

        return view('search.search', compact(['query', 'results']));

    }

}
