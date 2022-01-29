<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\StoreResourceHistory;
use App\Models\CustomUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct()
    {
        $auth = auth('api');
        /** @var Authenticatable&CustomUser|null $user */
        $user = $auth->user();

        if(! $auth->check() || ! $user?->hasMinPermission('admin')) {
            abort(403);
        }
    }

    /**
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
