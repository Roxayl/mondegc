<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Traits;

use Illuminate\Support\Str;

trait Resourceable
{
    use SimpleResourceable, GeneratesResourceHistory;

    /**
     * Donne le nom de la clé pour récupérer ou stocker les ressources générées dans le cache.
     *
     * @param  mixed  $parameters
     * @return string
     */
    public function resourceCacheKey(mixed $parameters = null): string
    {
        $stringParameters = null;
        if ($parameters !== null) {
            $stringParameters = Str::slug(json_encode($parameters), '-', 'en', [':' => '_']);
        }

        $key = str_replace('\\', '.', $this::class) . '.' . $this->getKey();
        if ($stringParameters) {
            $key .= '.' . $stringParameters;
        }

        return $key;
    }
}
