<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\CustomUser;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CustomUserController extends Controller
{
    /**
     * Vérifie que l'utilisateur passé en paramètre est actuellement connecté à l'application.
     *
     * @param  CustomUser  $user  L'utilisateur dont il faut tester le statut d'authentification.
     *
     * @throws AccessDeniedHttpException Lorsque l'utilisateur passé en paramètre n'est pas celui actuellement connecté.
     */
    private function checkCurrentUser(CustomUser $user): void
    {
        $authUser = auth()->user();

        if (! ($authUser instanceof CustomUser) || ! $user->is($authUser)) {
            abort(403);
        }
    }

    /**
     * Renvoie la liste des {@see Roleplayable roleplayables} de l'utilisateur connecté au format JSON.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function roleplayables(Request $request): JsonResponse
    {
        if (! auth()->check()) {
            abort(403);
        }

        /** @var CustomUser $user */
        $user = auth()->user();

        $type = Str::lower($request->input('type', ''));
        $term = Str::lower($request->input('term', ''));

        /** @var Collection<int, Roleplayable> $roleplayables */
        $roleplayables = $user->roleplayables()->filter(
            function (Roleplayable $roleplayable) use ($type, $term): bool {
                return Str::contains(Str::lower($roleplayable->getName()), $term)
                    && get_class($roleplayable) === 'Roxayl\MondeGC\Models\\' . Str::ucfirst($type);
            }
        );

        $result = [];
        foreach ($roleplayables as $roleplayable) {
            $result[] = [
                'id' => $roleplayable->getKey(),
                'value' => $roleplayable->getKey(),
                'label' => $roleplayable->getName(),
            ];
        }

        return response()->json($result);
    }

    /**
     * Met à jour le jeton d'authentification à l'API.
     *
     * @param  CustomUser  $user
     * @return RedirectResponse
     */
    public function updateToken(CustomUser $user): RedirectResponse
    {
        $this->checkCurrentUser($user);

        $user->api_token = Str::random(60);
        $user->save();

        return redirect()->back()->with('message', "success|Jeton d'authentification à l'API généré avec succès.");
    }
}
