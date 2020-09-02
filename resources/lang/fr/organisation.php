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
            "La puissance économique de l'alliance est composée des <strong>ressources des pays</strong>, ainsi que <strong>celles de l'alliance</strong>.",
            "L'alliance peut créer des <strong>infrastructures</strong>.",
            "Les ressources générées par l'alliance sont <strong>réparties entre les pays membres</strong>.",
            "Un pays ne peut rejoindre qu'<strong>une seule</strong> alliance.",
        ],
        'alliance-prerequisites' => [
            "Avoir au moins 2 membres",
            "Avoir 3 infrastructures validées",
            "Aucun membre ne doit déjà faire partie d'une alliance",
        ],

        'organisation' => "Organisation",
        'organisation-description' => 'Les organisations regroupent les diverses pays autour de thématiques communes : sport, culture...',
        'organisation-criteria' => [
            "La puissance économique de l'organisation est seulement composée des <strong>ressources propres</strong> de l'organisation.",
            "L'organisation peut créer des <strong>infrastructures</strong>.",
            "Les ressources générées par l'organisation sont <strong>réparties entre les pays membres</strong>.",
            "Un pays peut rejoindre <strong>plusieurs</strong> organisations.",
        ],
        'organisation-prerequisites' => [null],

        'group' => "Groupe d'États",
        'group-description' => "Les groupes d'États permettent de regrouper les pays, sans créer de liens économiques entre eux.",
        'group-criteria' => [
            "Le groupe d'États <strong>ne peut pas générer</strong> de ressources économiques.",
            "Un pays peut rejoindre <strong>plusieurs</strong> groupes d'États.",
        ],
        'group-prerequisites' => [
            "Ne pas avoir d'infrastructures"
        ],
    ],

    'create' => [
        'create-description' => "Vous êtes sur le point d'enrichir la vie diplomatique gécéenne en y ajoutant un nouvel acteur.<br>Chaque <strong>type d'organisation</strong> possède ses propres <strong>caractéristiques</strong>, afin de répondre à des besoins différents.<br>Une fois que votre organisation est suffisamment développée, vous pourrez peut-être la transformer en <strong>Alliance</strong>.",
    ],

    'validation' => [
        'migrate-alliance-error' => "Cette organisation ne peut pas devenir une alliance. Vérifiez que les conditions de ce type d'organisation sont respectées.",
        'migrate-group-error' => "Cette organisation ne peut pas devenir un groupe. Vérifiez que les conditions de ce type d'organisation sont respectées.",
        'migrate-too-early-error' => "Vous ne pouvez pas changer le type de votre organisation car vous l'avez déjà fait récemment. Vous devez attendre 7 jours après le précédent changement de type pour pouvoir le modifier à nouveau.",
    ],

];
