<?php

namespace App\Services;

use App\Models\CustomUser;
use GenCity\Monde;
use Illuminate\Auth\AuthenticationException;

class AuthenticationService
{
    public function __construct()
    {
        $this->startSession();
    }

    /**
     * Démarre la session PHP (via {@see session_start()}, si la session n'a pas déjà démarré.
     *
     * @return void
     */
    private function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Connecte l'utilisateur sur le site legacy et l'application Laravel.
     *
     * @param  CustomUser  $user
     * @return void
     */
    public function login(CustomUser $user): void
    {
        // Mettre à jour la date de dernière connexion.
        $user->ch_use_last_log = now();
        $user->save();

        $user = $user->refresh();

        // Stocker les informations de l'utilisateur connecté dans la session.
        $_SESSION['login_user'] = $user->ch_use_login;
        $_SESSION['pays_ID'] = $user->ch_use_paysID;
        $_SESSION['connect'] = true;
        $_SESSION['user_ID'] = $user->ch_use_id;
        $_SESSION['user_last_log'] = $user->ch_use_last_log;
        $_SESSION['statut'] = $user->ch_use_statut;
        $_SESSION['img_dirigeant'] = $user->ch_use_lien_imgpersonnage;
        $_SESSION['predicat_dirigeant'] = $user->ch_use_predicat_dirigeant;
        $_SESSION['titre_dirigeant'] = $user->ch_use_titre_dirigeant;
        $_SESSION['nom_dirigeant'] = $user->ch_use_nom_dirigeant;
        $_SESSION['prenom_dirigeant'] = $user->ch_use_prenom_dirigeant;
        $_SESSION['derniere_visite'] = $user->last_activity;
        $_SESSION['errormsgs'] = [];

        /**
         * @var Monde\User
         */
        $_SESSION['userObject'] = new Monde\User($user->ch_use_id);

        // Se connecter à l'application Laravel.
        auth()->login($user);
        // Réinitialiser le jeton CSRF.
        session()->regenerateToken();
    }

    /**
     * @param  int|string  $id
     * @return void
     */
    public function loginUsingId(int|string $id)
    {
        $user = CustomUser::find($id);

        if(! $user) {
            throw new AuthenticationException();
        }

        $this->login($user);
    }

    /**
     * Déconnecte l'utilisateur de l'application legacy et du site Laravel.
     *
     * @return void
     */
    public function logout(): void
    {
        // Effacement du cookie de session.
        setcookie('Session_mondeGC', '', time() -3600, null, null, false, false);
        unset($_COOKIE["Session_mondeGC"]);

        // Supprime les variables de session stockant les informations sur l'utilisateur.
        unset($_SESSION['login_user']);
        unset($_SESSION['pays_ID']);
        unset($_SESSION['PrevUrl']);
        unset($_SESSION['fond_ecran']);
        unset($_SESSION['connect']);
        unset($_SESSION['user_ID']);
        unset($_SESSION['user_last_log']);
        unset($_SESSION['statut']);
        unset($_SESSION['Temp_userID']);
        unset($_SESSION['userObject']);

        // Se déconnecter de l'application Laravel.
        auth()->logout();
        // Réinitialiser le jeton CSRF.
        session()->regenerateToken();
    }
}
