<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelCollection;
use App\Models\Repositories\Resource;
use App\Models\Repositories\Resourceable;

class ResourceableController extends Controller
{
    /**
     * @param string|null $type
     * @return ModelCollection
     */
    public function fetch(?string $type): ModelCollection
    {
        $shortModelName = Resource::getModels()[$type];
        $resourceable = (new Resourceable)->query()->fetch([$shortModelName])->get();

        return new ModelCollection($resourceable);
    }
}
