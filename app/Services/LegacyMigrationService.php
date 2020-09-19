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
     * Créé une instance du service de migration.
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * Exécute la migration.
     */
    public function run()
    {
        DB::beginTransaction();

        $count = count($this->queries);

        foreach($this->queries as $query) {
            print('<strong>1/' . $count . ': </strong>');
            print("Executing query: ");
            print('<pre>');
            print($query);
            print('</pre>');
            DB::statement($query);
        }

        DB::commit();
    }

    /**
     * Ajoute une requête à exécuter.
     * @param string $query Requête SQL raw.
     * @return $this
     */
    public function addQuery($query)
    {
        $this->queries[] = $query;

        return $this;
    }
}
