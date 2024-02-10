<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

abstract class Initializer extends Command
{
    protected function initTestingEnv(): void
    {
        $this->line('Copie de .env --> .env.testing');

        if (! File::exists(base_path('.env'))) {
            throw new FileNotFoundException('Fichier .env non existant.');
        }

        $testingPath = base_path('.env.testing');

        copy(base_path('.env'), $testingPath);

        if (file_exists($testingPath)) {
            $this->saveEnvValueToFile($testingPath, 'DB_DATABASE', 'mondegc_testing', 'mondegc');
            $this->line('Base de données dans .env.testing modifiée');
        }
    }

    /**
     * Modifie la valeur d'une clé dans un fichier .env.
     *
     * @param  string  $path
     * @param  string  $key
     * @param  string  $newValue
     * @param  string|null  $oldValue
     * @return bool Renvoie <code>true</code> en cas de succès lors de l'écriture de la nouvelle valeur,
     *              <code>false</code> sinon.
     */
    protected function saveEnvValueToFile(string $path, string $key, string $newValue, ?string $oldValue = null): bool
    {
        if ($oldValue === null) {
            $oldValue = '';
        }

        if (! file_exists($path)) {
            return false;
        }

        $success = file_put_contents($path, str_replace(
            "\n$key=$oldValue",
            "\n$key=" . $newValue,
            file_get_contents($path)
        ));

        return ! ($success === false);
    }
}
