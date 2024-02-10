<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Console\Commands;

use Illuminate\Console\Command;

class GenerateHtaccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:generate-htaccess';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Génère un fichier .htaccess au répertoire racine, "
        . "selon la configuration de l'application";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $template = file_get_contents(resource_path('templates/htaccess-template.txt'));

        $directoryPath = config('app.directory_path');
        if(! empty($directoryPath)) {
            $directoryPath = '/' . $directoryPath;
        }

        $this->line('Replacing template with directory path: "' . $directoryPath . '"');
        $template = str_replace('{{$directory}}', $directoryPath, $template);

        $htaccessPath = base_path('.htaccess');
        $this->line("Updating root .htaccess file at $htaccessPath");
        file_put_contents($htaccessPath, $template, LOCK_EX);

        $this->info('.htaccess file generated successfully.');

        return 0;
    }
}
