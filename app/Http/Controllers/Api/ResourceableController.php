<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceableCollection;
use App\Models\Repositories\Resourceable;
use Illuminate\Http\Request;

class ResourceableController extends Controller
{
    public function index(Request $request)
    {
        $resourceable = (new Resourceable)->query()->all()->withResources()->get();

        return new ResourceableCollection($resourceable);
    }
}
