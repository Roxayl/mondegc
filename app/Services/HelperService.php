<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ViewErrorBag;

class HelperService
{
    /**
     * @return string
     */
    public static function displayAlert(): string
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

    /**
     * @param string $element
     * @param scalar|array $data
     * @return string
     */
    public static function renderLegacyElement(string $element, $data): string
    {
        if(!is_array($data)) {
            $data = array($data);
        }

        ob_start();
        require(base_path('legacy/php/elements/' . $element . '.php'));
        return ob_get_clean();
    }

    /**
     * @param string|null $text
     * @return string
     */
    public static function purifyHtml(?string $text): string
    {
        static $purifier = null;
        if(is_null($purifier)) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.SafeIframe', true);
            $config->set('URI.SafeIframeRegexp',
                '%^https://(.*)%');
            $purifier = new HTMLPurifier($config);
        }
        return $purifier->purify($text);
    }
}
