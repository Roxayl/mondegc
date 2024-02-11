<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Roxayl\MondeGC\Models\Communique;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Patrimoine;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Roleplay;
use Roxayl\MondeGC\Models\Ville;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    /**
     * Renvoie la page de l'outil de recherche.
     *
     * @param  Request  $request
     * @param  SessionManager  $session
     * @return View
     */
    public function index(Request $request, SessionManager $session): View
    {
        $query = $request->input('query', '');
        if (empty($query)) {
            return view('search.search');
        }

        $search = new Search();

        if (strlen($query) < 2) {
            $session->flash('message', 'error|Veuillez renseigner au moins 2 caractères.');
        } else {
            $search
                ->registerModel(Pays::class, ['ch_pay_nom'])
                ->registerModel(Organisation::class, ['name'])
                ->registerModel(Ville::class, ['ch_vil_nom'])
                ->registerModel(Roleplay::class, ['name'])
                ->registerModel(Patrimoine::class, ['ch_pat_nom'])
                ->registerModel(Communique::class, function (ModelSearchAspect $aspect): void {
                    $aspect->addSearchableAttribute('ch_com_titre')
                        ->addSearchableAttribute('ch_com_contenu')
                        ->mainPosts()
                        ->orderBy('ch_com_date', 'DESC')
                        ->limit(500);
                });
        }

        $results = $search->search($query);

        return view('search.search', compact(['query', 'results']));
    }
}
