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
        'mondegc' => 'https://discord.com/api/webhooks/883724949800501328/O5e0_q0-8tPPxQ0hpIuarlvQxQ3qt13Jv8Ryd9RvNiS9UpC9ioqCNktvgMQ8RvvZJwj4',
        'ocgc' => 'https://discord.com/api/webhooks/884073055880618044/xmyL-3wkSqhO3eUuKgA8H2jBarpl-6NCWD2Pt0UUQ4QfRISIYknW0v0rO2S3SEv0F9f8',
        'debug' => 'https://discord.com/api/webhooks/883740414413246495/WZmRrUv4FUGy2TJZqo11b3XGcMmw7ZBugNtdVyiP6mb7181hvsJuCXBWzOo4WM-jLgnj',
    ],

];
