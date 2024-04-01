<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Services\Gameplay;

use Illuminate\Cache\CacheManager;
use Illuminate\Database\DatabaseManager;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Factories\InfluencableFactory;
use Roxayl\MondeGC\Models\Influence;

class RegenerateInfluenceService
{
    public function __construct(
        private readonly InfluencableFactory $influencableFactory,
        private readonly DatabaseManager $db,
        private readonly CacheManager $cache
    ) {
    }

    /**
     * Régénère les influences générées par les entités influençables.
     */
    public function regenerate(): void
    {
        /** @var Influencable[] $influencables */
        $influencables = $this->influencableFactory->listEnabled();

        $this->db->transaction(function () use ($influencables) {
            $this->db->table('influence')->delete();

            foreach ($influencables as $influencable) {
                $influencable->generateInfluence();
            }
        });

        $this->cache->flush();
    }

    /**
     * Donne le nombre d'enregistrements dans la table "influences".
     *
     * @return int
     */
    public function influenceCount(): int
    {
        return Influence::count();
    }
}
