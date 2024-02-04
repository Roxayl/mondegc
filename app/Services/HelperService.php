<?php

namespace Roxayl\MondeGC\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Roxayl\MondeGC\View\Components\Blocks\Flash;

class HelperService
{
    /**
     * @return string
     */
    public static function displayAlert(): string
    {
        return app(Flash::class)->render();
    }

    /**
     * @param string $element
     * @param array|scalar $data
     * @return string
     */
    public static function renderLegacyElement(string $element, float|array|bool|int|string $data): string
    {
        if(!is_array($data)) {
            $data = [$data];
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
