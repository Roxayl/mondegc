<?php

namespace App\Services;

class EconomyService
{
    public static function resourcesPrefilled()
    {
        $return = [];

        foreach(config('enums.resources') as $resource) {
            $return[$resource] = 0;
        }

        return $return;
    }
}
