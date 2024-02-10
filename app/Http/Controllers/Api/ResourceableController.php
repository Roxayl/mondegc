<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Roxayl\MondeGC\Http\Controllers\Controller;
use Roxayl\MondeGC\Models\Repositories\BaseRepository;
use Roxayl\MondeGC\Models\Repositories\Resource;
use Roxayl\MondeGC\Models\Repositories\Resourceable;

class ResourceableController extends Controller
{
    /**
     * /resourceable
     *
     * Liste d'entités générant des ressources.
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

        $page = $request->input('page') ?? 1;
        $perPage = BaseRepository::perPage;

        $repository = (new Resourceable)->query()->fetch([$shortModelName])->paginate($page);
        $collection = $repository->get();

        $paginator = new LengthAwarePaginator(
            $collection,
            $repository->getTotalCount(),
            $perPage,
            $page);

        return new ResourceCollection($paginator);
    }
}
