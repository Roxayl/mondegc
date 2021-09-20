<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use App\View\Components\Blocks\ScriptConfiguration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TorMorten\Eventy\Facades\Eventy;

class LegacySiteController extends Controller
{
    public function __invoke(Request $request, string $path = ""): Response
    {
        // On va appeler les événements du site legacy !

        // Ici, on affiche une balise <script> avec les infos de configuration, juste avant
        // la balise </head>.
        Eventy::addAction('display.legacy.beforeHeadClosingTag', function() {
            echo (new ScriptConfiguration)->render();
        });

        // Appeler le front controller et retourner une réponse.
        return response(include base_path("public/front.php"));
    }
}
