<?php

namespace Roxayl\MondeGC\Services;

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

    /**
     * Calcule la taille d'un répertoire.
     *
     * @param string $directory
     * @return float
     */
    public static function directorySize(string $directory): float
    {
        $size = 0;
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
            if($file->getFileName() !== '..') {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    /**
     * Format des bytes sous forme lisible.
     *
     * Récupéré depuis https://stackoverflow.com/a/2510459
     *
     * @param  float  $bytes
     * @param  int  $precision
     * @return string
     */
    public static function formatBytes(float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Décommenter l'un ou l'autre...
        // $bytes /= pow(1024, $pow);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
