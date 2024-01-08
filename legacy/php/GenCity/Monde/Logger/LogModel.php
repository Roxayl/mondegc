<?php

namespace GenCity\Monde\Logger;

use Squirrel\ModelStructure;

class LogModel extends ModelStructure
{
    static string $tableName = 'log';

    static string $primary_key = 'id';
}
