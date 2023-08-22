<?php

namespace Roxayl\MondeGC\Models\Traits;

use Illuminate\Support\Str;

trait Resourceable
{
    use SimpleResourceable, GeneratesResourceHistory;

    /**
     * Donne le nom de la clé pour récupérer ou stocker les ressources générées dans le cache.
     *
     * @param null $parameters
     * @return string
     */
    public function resourceCacheKey($parameters = null): string
    {
        $stringParameters = null;
        if($parameters !== null) {
            $stringParameters = Str::slug(json_encode($parameters), '-', 'en', [':' => '_']);
        }

        $key = str_replace('\\', '.', $this::class) . '.' . $this->getKey();
        if($stringParameters) {
            $key .= '.' . $stringParameters;
        }

        return $key;
    }
}
