<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LegacyMigrationService
{
    /**
     * @var array Liste de requêtes.
     */
    private array $queries = [];

    /**
     * Exécute la migration.
     */
    public function run(): void
    {
        DB::beginTransaction();

        $count = count($this->queries);

        $cpt = 1;
        foreach($this->queries as $query) {
            print("<strong>$cpt/$count : </strong>");
            print("Executing query: ");
            print('<pre>');
            print($query);
            print('</pre>');
            DB::statement($query);
            $cpt++;
        }

        DB::commit();
    }

    /**
     * Ajoute une requête à exécuter.
     * @param string $query Requête SQL raw.
     * @return $this
     */
    public function addQuery(string $query): self
    {
        $this->queries[] = $query;

        return $this;
    }
}
