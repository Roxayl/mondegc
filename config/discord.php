<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bot Discord.
    |--------------------------------------------------------------------------
    |
    | Définit le token du bot Discord, accessible depuis l'interface de
    | développeur de Discord.
    |
    */

    'token' => env('DISCORD_BOT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Discord.
    |--------------------------------------------------------------------------
    |
    | Les webhooks de Discord sont définis ici. Il y a autant d'entrées que
    | d'URL de webhooks, décris avec une clé.
    |
    */

    'webhookName' => 'SquirrelBot',

    'webhookUrl' => [
        'debug' => env('DISCORD_WEBHOOKURL_DEBUG'),
        'mondegc' => env('DISCORD_WEBHOOKURL_MONDEGC'),
        'ocgc' => env('DISCORD_WEBHOOKURL_OCGC'),
        'private' => env('DISCORD_WEBHOOKURL_PRIVATE'),
    ],

];
