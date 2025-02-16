<?php

use Illuminate\Contracts\Foundation\Application;

/**
 * @returns array<string, bool>
 */
return static function (Application $app): array {
    return [
        'roleplay' => true,
        'roleplay-only-restricted' => false,
        'cache' => true,
        'subdivision' => false,
    ];
};
