<?php

namespace Roxayl\MondeGC\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;

/**
 * Cette classe permet de compiler un template Blade à partir d'une chaîne de caractères.
 *
 * @see https://stackoverflow.com/questions/16891398/is-there-any-way-to-compile-a-blade-template-from-a-string
 */
class StringBladeService
{
    /**
     * @var Filesystem
    */
    protected Filesystem $file;

    /**
     * @var \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    */
    protected $viewer;

    /**
     * StringBlade constructor.
     *
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;
        $this->viewer = view();
    }

    /**
     * Get Blade file path.
     *
     * @param string $bladeString
     * @return string
     * @throws FileNotFoundException
     */
    protected function getBlade(string $bladeString): string
    {
        $bladePath = $this->generateBladePath();

        $content = Blade::compileString($bladeString);

        if(! $this->file->put($bladePath, $content)) {
            throw new FileNotFoundException("Impossible de créer un fichier temporaire.");
        }
        return $bladePath;
    }

    /**
     * Get the rendered HTML.
     *
     * @param string $bladeString
     * @param array $data
     * @return bool|string
     * @throws FileNotFoundException
     */
    public function render(string $bladeString, array $data = [])
    {
        // Put the php version of blade String to *.php temp file & returns the temp file path
        $bladePath = $this->getBlade($bladeString);

        // Render the php temp file & return the HTML content
        $content = $this->viewer->file($bladePath, $data)->render();

        // Delete the php temp file.
        $this->file->delete($bladePath);

        return $content;
    }

    /**
     * Generate a blade file path.
     *
     * @return string
     */
    protected function generateBladePath(): string
    {
        $cachePath = rtrim(config('cache.stores.file.path'), '/');
        $tempFileName = sha1('string-blade' . microtime());
        $directory = "{$cachePath}/string-blades";

        if(!is_dir($directory)) {
            mkdir($directory);
        }

        return "{$directory}/{$tempFileName}.php";
    }
}
