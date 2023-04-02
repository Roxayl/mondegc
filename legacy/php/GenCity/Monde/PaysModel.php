<?php

namespace GenCity\Monde;

use Squirrel\ModelStructure;

class PaysModel extends ModelStructure
{
    static string $tableName = 'pays';

    static string $primary_key = 'ch_pay_id';
}
