<?php

namespace GenCity\Monde\Temperance;

use Squirrel\ModelStructure;

class InfraOfficielleModel extends ModelStructure
{
    static string $tableName = 'infrastructures_officielles';

    static string $primary_key = 'ch_inf_off_id';
}
