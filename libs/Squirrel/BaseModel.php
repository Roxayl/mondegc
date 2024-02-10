<?php

namespace Squirrel;

class BaseModel
{
    /**
     * @var ModelStructureInterface
     */
    public ModelStructureInterface $model;

    public function __get(string $prop): mixed
    {
        return $this->get($prop);
    }

    public function get(string $prop): mixed
    {
        if (is_array($this->model)) {
            return $this->model[$prop];
        } else {
            return $this->model->$prop;
        }
    }

    public function set(string $prop, mixed $value): void
    {
        if (is_array($this->model)) {
            $this->model[$prop] = $value;
        } else {
            $this->model->$prop = $value;
        }
    }

    public function update(): void
    {
        $structure = $this->model->getStructure();

        $query = 'UPDATE ' . $this->{$this->model::$tableName} . ' SET ';

        foreach ($structure as $field => $default) {
            $query .= ' `' . $field . '` = ' . escape_sql($this->get($field));
            end($structure);
            if ($field !== key($structure)) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE id = ' . escape_sql($this->get('id'));
        mysql_query($query);
    }
}
