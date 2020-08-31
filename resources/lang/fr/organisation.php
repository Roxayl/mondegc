<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Textes liées aux organisations.
    |--------------------------------------------------------------------------
    |
    */

    'types' => [
        'alliance' => "Alliance",
        'alliance-description' => "Les alliances sont au coeur des enjeux diplomatiques du Monde GC. Ce sont des unions économiques, politiques et militaire de premier ordre.",
        'alliance-criteria' => [
            "La puissance économique de l'alliance est composée des ressources des pays, ainsi que celles de l'alliance.",
            "L'alliance peut créer des infrastructures.",
            "Les ressources générées par l'alliance sont réparties entre les pays membres.",
            "Un pays ne peut rejoindre qu'une seule alliance.",
        ],

        'organisation' => "Organisation",
        'organisation-description' => 'Les organisations regroupent les diverses pays autour de thématiques communes : sport, culture...',
        'organisation-criteria' => [
            "La puissance économique de l'organisation est composée des ressources propres de l'organisation.",
            "L'organisation peut créer des infrastructures.",
            "Les ressources générées par l'organisation sont réparties entre les pays membres.",
            "Un pays peut rejoindre plusieurs organisations.",
        ],

        'group' => "Groupe d'États",
        'group-description' => "Les groupes d'États permettent de regrouper les pays, sans créer de liens économiques entre eux.",
        'group-criteria' => [
            "Le groupe d'États ne peut générer de ressources économiques.",
            "Un pays peut rejoindre plusieurs groupes d'États.",
        ],
    ],

];
