<?php

namespace Roxayl\MondeGC\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Roxayl\MondeGC\Http\Controllers\Controller;
use Roxayl\MondeGC\Models\Repositories\Resource;

class ResourceController extends Controller
{
    /**
     * /resource
     *
     * Ressources générées par les entités.
     *
     * @param Request $request
     * @param string $type
     * @return ResourceCollection
     *
     * @urlParam  type  string required  Type de l'entité générant des ressources. Prend les valeurs "ville", "pays",
     *                                   ou "organisation". Example: organisation
     */
    public function fetch(Request $request, string $type): ResourceCollection
    {
        $shortModelName = Resource::getModels()[$type];

        /* $page = $request->input('page') ?? 1; */
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
