<?php

namespace App\Http\Controllers;

use App\Models\CustomUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CustomUserController extends Controller
{
    /**
     * Vérifie que l'utilisateur passé en paramètre est actuellement connecté à l'application.
     * @param  CustomUser  $user L'utilisateur dont il faut tester le statut d'authentification.
     * @return void
     * @throws AccessDeniedHttpException Lorsque l'utilisateur passé en paramètre n'est pas celui actuellement connecté.
     */
    private function checkCurrentUser(CustomUser $user)
    {
        $authUser = auth()->user();

        if(! ($authUser instanceof CustomUser) || ! $user->is($authUser)) {
            abort(403);
        }
    }

    /**
     * Met à jour le jeton d'authentification à l'API.
     * @param  Request     $request
     * @param  CustomUser  $user
     * @return RedirectResponse
     */
    public function updateToken(Request $request, CustomUser $user): RedirectResponse
    {
        $this->checkCurrentUser($user);

        $user->api_token = Str::random(60);
        $user->save();

        return redirect()->back()->with('success', "Jeton d'authentification à l'API généré avec succès.");
    }
}
