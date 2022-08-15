<?php

namespace App\Models\Traits;

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
            $stringParameters = json_encode($parameters);
        }

        $key = str_replace('\\', '.', $this::class) . '.' . $this->getKey();
        if($stringParameters) {
            $key .= '.' . $stringParameters;
        }

        return $key;
    }
}
