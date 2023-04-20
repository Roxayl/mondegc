<?php

namespace GenCity\Monde;

use Squirrel\ModelStructureInterface;

class PageModel implements ModelStructureInterface
{
    static string $tableName = 'legacy_pages';

    private array $info;

    public function __construct(int|string|array|null $data = null)
    {
        if(is_scalar($data)) {
            $this->info = $this->populate($data);
        } else {
            $this->info = $data;
        }
    }

    private function populate(int|string $id): mixed
    {
        $query = mysql_query(sprintf('SELECT * FROM legacy_pages WHERE this_id = %s',
            escape_sql($id, 'text')));
        return mysql_fetch_assoc($query);
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    public function __get(string $prop): mixed
    {
        if(!isset($this->info[$prop]))
            throw new \Exception("La propriété $prop n'est pas définie pour la classe " . __CLASS__);
        return $this->info[$prop];
    }

    public function __set(string $prop, mixed $value): void
    {
        $this->info[$prop] = $value;
    }
}
