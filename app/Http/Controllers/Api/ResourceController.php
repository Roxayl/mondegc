<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceCollection;
use App\Models\Repositories\Resource;

class ResourceController extends Controller
{
    /**
     * @param string $type
     * @return ResourceCollection
     */
    public function fetch(string $type): ResourceCollection
    {
        $shortModelName = Resource::getModels()[$type];
        $resources = (new Resource)->query()->fetch([$shortModelName])->get();

        return new ResourceCollection($resources);
    }
}
