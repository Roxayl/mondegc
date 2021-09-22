<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LegacySiteController extends Controller
{
    public function __invoke(Request $request, string $path = ""): Response
    {
        // Appeler le front controller et retourner une réponse.
        return response(include base_path("public/front.php"));
    }
}
