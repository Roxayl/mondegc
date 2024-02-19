<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers\Api;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Roxayl\MondeGC\Http\Controllers\Controller;
use Roxayl\MondeGC\Jobs\StoreResourceHistory;
use Roxayl\MondeGC\Models\CustomUser;

class AdminController extends Controller
{
    public function __construct()
    {
        $auth = auth('api');
        /** @var Authenticatable&CustomUser|null $user */
        $user = $auth->user();

        if (! $auth->check() || ! $user?->hasMinPermission('admin')) {
            abort(403);
        }
    }

    /**
     * /admin/store-resource-history.
     *
     * Exécute l'historisation des ressources des entités ressourçables.
     *
     * @return JsonResponse
     */
    public function storeResourceHistory(): JsonResponse
    {
        app()->make(StoreResourceHistory::class)->handle();

        return response()->json([
            'status' => 'success',
            'message' => 'Tâche exécutée avec succès !.',
        ]);
    }
}
