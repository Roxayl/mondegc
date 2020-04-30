<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;

class LegacySiteController extends Controller {

    public function index($path = "") {
        ob_start();
        include base_path("public/front.php");
        return response( ob_get_clean() );
    }

}