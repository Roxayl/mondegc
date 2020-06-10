<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LegacySiteController extends Controller {

    public function index(Request $request, $path = "") {
        return response(include base_path("public/front.php"));
    }

    public function authFallback(Request $request) {
        throw new AccessDeniedHttpException("Accès refusé");
    }

}