<?php

namespace GenCity\Proposal;
use Squirrel\ModelStructureInterface;


class ProposalModel implements ModelStructureInterface
{
    static string $tableName = 'ocgc_proposals';

    private array $info;

    private static array $structure = [];

    public function __construct(int|string|array|null $data = null) {

        if(is_numeric($data)) {
            $this->info = $this->populate($data);
        } elseif(is_null($data)) {
            $this->info = $this->getStructure();
        } else {
            $this->info = $data;
        }

        // Initialiser le vote blanc, qu'on évitera de stocker dans la base de données.
        $this->info['reponse_0'] = 'Blanc';

    }

    private function populate(int|string $id): mixed
    {
        $query = mysql_query(sprintf('SELECT * FROM '. self::$tableName . ' WHERE id = %s',
            escape_sql($id, 'int')));
        return mysql_fetch_assoc($query);
    }

    public function getStructure(): array
    {
        if(! array_key_exists(self::$tableName, self::$structure)) {
            $query = mysql_query('DESCRIBE ' . self::$tableName);
            $finalTable = [];
            while($row = mysql_fetch_assoc($query)) {
                $finalTable[$row['Field']] = $row['Default'];
            }
            self::$structure[self::$tableName] = $finalTable;
        }

        return self::$structure[self::$tableName];
    }

    public function __get(string $prop): mixed
    {
        return $this->info[$prop];
    }

    public function __set(string $prop, mixed $value): void
    {
        $this->info[$prop] = $value;
    }
}
