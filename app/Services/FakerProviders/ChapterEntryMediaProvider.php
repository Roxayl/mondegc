<?php

namespace Roxayl\MondeGC\Services\FakerProviders;

use Faker\Provider\Base;

class ChapterEntryMediaProvider extends Base
{
    protected static array $mediaTypes = [
        'squirrel.squit' => [
            [
                'meta' => [
                    'url' => 'https://squirrel.roxayl.fr/squit/37662#perm',
                ],
                'media' => [
                    'author' => 'Clément Ferrand',
                    'text' => "Nous revendiquons la partie en bleu du pôle sud. Nous réfléchirons à en faire un statut de "
                            . "TOM dans les prochaines semaines afin que l'OCGC valide notre revendication.",
                    'date' => '2020-12-12 12:58:00',
                ],
            ],
            [
                'meta' => [
                    'url' => 'https://squirrel.roxayl.fr/squit/37654#perm',
                ],
                'media' => [
                    'author' => 'José San Garcia',
                    'text' => "Le premier rapport sur les ressources naturelles avérées et probables au pôle nord est "
                            . "presque prêt. En attendant voici les cartes des pôles et de leur contexte géographique.",
                    'date' => '2021-06-20 14:35:12',
                ],
            ]
        ],
        'forum.post' => [
            [
                'meta' => [
                    'url' => 'https://www.forum-gc.com/t2501p110-shibubu-reviendra#295617',
                ],
                'media' => [
                    'author' => 'vallamir',
                    'text' => "Yes ;) Je te concocte un petit truc pour notre partenariat Océania Rail en attendant ;)",
                    'date' => '2020-12-12 12:58:00',
                ],
            ],
            [
                'meta' => [
                    'url' => 'https://www.forum-gc.com/t2501p100-shibubu-reviendra#295608',
                ],
                'media' => [
                    'author' => 'romu23',
                    'text' => "Faut-il dire Shibubu ou Shiboubouille ?",
                    'date' => '2021-06-20 14:35:12',
                ],
            ]
        ],
    ];

    /**
     * Donne le corps d'un média, pour un type de média donné.
     *
     * @param  string  $mediaType
     * @return array
     */
    public function chapterEntryMediaData(string $mediaType): array
    {
        if(! array_key_exists($mediaType, self::$mediaTypes)) {
            throw new \InvalidArgumentException("Le type de média n'existe pas.");
        }

        return static::randomElement(self::$mediaTypes[$mediaType]);
    }

    /**
     * Renvoie un type de média d'entrée de chapitre aléatoire.
     *
     * @return string
     */
    public function chapterEntryMediaType(): string
    {
        $types = array_keys(self::$mediaTypes);

        return static::randomElement($types);
    }
}
