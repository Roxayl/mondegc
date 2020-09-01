<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ViewErrorBag;

class HelperService {

    static function displayAlert() : string
    {
        $output = '';

        // Messages généralement définis pendant une redirection.
        if (Session::has('message')) {
            list($type, $message) = explode('|', Session::get('message'));
            $output .= sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
        }

        // Affiche le contenu de la variable 'errors', notamment pour la validation.
        $errors = request()->session()->get('errors') ?: new ViewErrorBag;
        if($errors->any()) {
            $output .= '<div class="alert alert-danger"><ul>';
            foreach ($errors->all() as $error) {
                $output .= sprintf('<li>%s</li>', $error);
            }
            $output .= '</ul></div>';
        }

        return $output;
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