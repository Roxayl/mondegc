<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Services;

use GenCity\Monde;
use Illuminate\Auth\AuthenticationException;
use Roxayl\MondeGC\Models\CustomUser;

class AuthenticationService
{
    public function __construct()
    {
        $this->startSession();
    }

    /**
     * Démarre la session PHP (via {@see session_start()}, si la session n'a pas déjà démarré.
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
        $_SESSION['errmsgs'] ??= [];

        $_SESSION['userObject'] = new Monde\User($user->ch_use_id);

        // Se connecter à l'application Laravel.
        auth()->login($user);
        // Réinitialiser le jeton CSRF.
        session()->regenerateToken();
    }

    /**
     * @param  int  $id
     */
    public function loginUsingId(int $id): void
    {
        $user = CustomUser::query()->find($id);

        if(! $user) {
            throw new AuthenticationException();
        }

        $this->login($user);
    }

    /**
     * Déconnecte l'utilisateur de l'application legacy et du site Laravel.
     */
    public function logout(): void
    {
        // Effacement du cookie de session.
        setcookie('Session_mondeGC', '', time() - 7200,  '', '', false, true);
        unset($_COOKIE['Session_mondeGC']);

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
