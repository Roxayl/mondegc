<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;

class LegacySiteController extends Controller {

    public function index($path = "") {
        return response(include base_path("public/front.php"));
    }

}