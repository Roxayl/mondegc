<?php

namespace Roxayl\MondeGC\Http\Controllers\Legacy;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Roxayl\MondeGC\Http\Controllers\Controller;

class LegacySiteController extends Controller
{
    public function __invoke(Request $request, string $path = ""): Response
    {
        // Appeler le front controller et retourner une réponse.
        return response(include base_path("public/front.php"));
    }
}
