<?php

namespace Roxayl\MondeGC\Services\FakerProviders;

use Faker\Provider\Base;

/**
 * Cette classe est un provider pour Faker qui permet de générer des noms d'événements, utiles pour seeder des noms
 * de roleplay, par exemple.
 */
class EventNameProvider extends Base
{
    /**
     * @var string[] Noms d'événements qui seront générés aléatoirement via la méthode {@see eventName()}.
     */
    protected static array $names = [
        'Jeux Olympiques de 2022',
        'Exposition universelle de Galax 2020',
        'Guerre de la Mer de Batchy',
        'Guerre des pôles',
        'Elections présidentielles en Harada 2020',
        'Grande Guerre Gécéenne',
        'Attaque contre la Banque centrale du Polaro',
    ];

    /**
     * @return string
     */
    public function eventName(): string
    {
        return static::randomElement(static::$names);
    }
}
