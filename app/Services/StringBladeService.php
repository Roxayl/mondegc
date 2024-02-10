<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Compilers\BladeCompiler;

/**
 * Cette classe permet de compiler un template Blade à partir d'une chaîne de caractères.
 *
 * @see https://stackoverflow.com/questions/16891398/is-there-any-way-to-compile-a-blade-template-from-a-string
 * @deprecated Désormais implémenté nativement via {@see \Illuminate\View\Compilers\BladeCompiler::render()}
 *             dans Laravel 9.
 */
class StringBladeService
{
    public function __construct(
        protected readonly Filesystem $file,
        protected readonly View|ViewFactory $view,
        protected readonly BladeCompiler $blade
    ) {
    }

    /**
     * Obtenir le chemin d'un template temporaire Blade.
     *
     * @param string $bladeString
     * @return string
     * @throws FileNotFoundException
     */
    protected function getBlade(string $bladeString): string
    {
        $bladePath = $this->generateBladePath();

        $content = $this->blade->compileString($bladeString);

        if(! $this->file->put($bladePath, $content)) {
            throw new FileNotFoundException("Impossible de créer un fichier temporaire.");
        }
        return $bladePath;
    }

    /**
     * Afficher le rendu HTML.
     *
     * @param string $bladeString
     * @param array $data
     * @return string
     * @throws FileNotFoundException
     */
    public function render(string $bladeString, array $data = []): string
    {
        // Put the php version of blade String to *.php temp file & returns the temp file path
        $bladePath = $this->getBlade($bladeString);

        // Render the php temp file & return the HTML content
        $content = $this->view->file($bladePath, $data)->render();

        // Delete the php temp file.
        $this->file->delete($bladePath);

        return $content;
    }

    /**
     * Générer un chemin temporaire pour le template Blade.
     *
     * @return string
     */
    protected function generateBladePath(): string
    {
        $cachePath = rtrim(config('cache.stores.file.path'), '/');
        $tempFileName = sha1('string-blade' . microtime());
        $directory = "$cachePath/string-blades";

        if(!is_dir($directory)) {
            mkdir($directory);
        }

        return "$directory/$tempFileName.php";
    }
}
