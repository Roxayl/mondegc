<?php

namespace GenCity\Monde;

use Squirrel\ModelStructureInterface;

class VilleModel implements ModelStructureInterface
{
    static string $tableName = 'villes';

    private array $info;

    public function __construct(int|string|array|null $data = null)
    {
        if(is_numeric($data)) {
            $this->info = $this->populate($data);
        } else {
            $this->info = $data;
        }
    }

    private function populate(int|string $id): mixed
    {
        $query = mysql_query(sprintf('SELECT * FROM villes WHERE ch_vil_id = %s',
            GetSQLValueString($id, 'int')));
        return mysql_fetch_assoc($query);
    }

    public function __get(string $prop): mixed
    {
        if(!isset($this->info[$prop]) && $this->info[$prop] !== null)
            throw new \Exception("La propriété $prop n'est pas définie pour la classe " . __CLASS__);
        return $this->info[$prop];
    }

    public function __set(string $prop, mixed $value): void
    {
        $this->info[$prop] = $value;
    }
}
