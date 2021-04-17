<?php

use Illuminate\Support\Str;

return [

    'version' => env('LEGACY_VERSION', '2.x'),

    'hide_errors' => env('LEGACY_HIDE_ERRORS', false),

    'enable_csrf_protection' => env('LEGACY_CSRF', true),

    'salt' => env('LEGACY_SALT', Str::random(32)),

];
