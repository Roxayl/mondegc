<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class HelperService {

    static function displayAlert() {

        if (Session::has('message')) {

            list($type, $message) = explode('|', Session::get('message'));

            return sprintf('<div class="alert alert-%s">%s</div>', $type, $message);

        }

        return '';

    }

}