<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Session;

class HelperService {

    static function displayAlert()
    {
        if (Session::has('message')) {
            list($type, $message) = explode('|', Session::get('message'));
            return sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
        }

        return '';
    }

    static function renderLegacyElement($element, $data)
    {
        if(!is_array($data))
            $data = array($data);
        ob_start();
        require(base_path('php/elements/' . $element . '.php'));
        return ob_get_clean();
    }

    static function purifyHtml($text)
    {
        static $purifier = null;
        if(is_null($purifier)) {
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
        }
        return $purifier->purify($text);
    }

}