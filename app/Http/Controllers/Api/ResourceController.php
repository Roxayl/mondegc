<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Resource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceController extends Controller
{
    /**
     * @param Request $request
     * @param string|null $type
     * @return ResourceCollection
     */
    public function fetch(Request $request, ?string $type): ResourceCollection
    {
        $shortModelName = Resource::getModels()[$type];

        $page = $request->input('page') ?? 1;
        // FIXME: la pagination ne fonctionne pas pour une raison ou une autre...
        /* $perPage = BaseRepository::perPage; */

        $repository = (new Resource)->query()->fetch([$shortModelName])/*->paginate($page)*/;
        $collection = $repository->get();

        /*
        $paginator = new LengthAwarePaginator(
            $collection,
            $repository->getTotalCount(),
            $perPage,
            $page); */

        return new ResourceCollection($collection);
    }
}
