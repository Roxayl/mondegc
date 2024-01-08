<?php

namespace Squirrel;

abstract class ModelStructure implements ModelStructureInterface
{
    static string $tableName;

    static string $primary_key;

    public array $info = [];

    private static array $structure = [];

    public function __construct(int|string|array|null $data = null)
    {
        if(is_numeric($data)) {
            $this->info = $this->populate($data);
        } elseif(is_null($data)) {
            $this->info = $this->getStructure();
        } else {
            $this->info = $data;
        }
    }

    protected function populate(int|string $id): mixed
    {
        $sql = sprintf('SELECT * FROM ' . static::$tableName .
            ' WHERE ' . $this::$primary_key .' = %s',
            escape_sql($id, 'int'));
        $query = mysql_query($sql);
        return mysql_fetch_assoc($query);
    }

    public function getStructure(): mixed
    {
        if(! array_key_exists(static::$tableName, self::$structure)) {
            $query = mysql_query('DESCRIBE ' . static::$tableName);
            $finalTable = [];
            while($row = mysql_fetch_assoc($query)) {
                $finalTable[$row['Field']] = $row['Default'];
            }
            self::$structure[static::$tableName] = $finalTable;
        }

        return self::$structure[static::$tableName];
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    public function __get($prop): mixed
    {
        return $this->info[$prop];
    }

    public function __set(string $prop, mixed $value): void
    {
        $this->info[$prop] = $value;
    }
}
