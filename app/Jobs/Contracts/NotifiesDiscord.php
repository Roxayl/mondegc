<?php

namespace App\Jobs\Contracts;

use Illuminate\Database\Eloquent\Model;

interface NotifiesDiscord
{
    /**
     * Paramètres à envoyer via le webhook.
     * @return array
     */
    public function generatePayload(): array;

    /**
     * Donne le modèle concerné par cette notification.
     * @return Model
     */
    public function getModelIdentifier(): Model;

    /**
     * Définit si la notification doit être unique, pour un channel, un type et un identifiant de modèle donné.
     * @return bool
     */
    public function isUnique(): bool;

    /**
     * Renvoie le nom du webhook où cette notification doit être publiée.
     * @return string
     */
    public function getWebhookName(): string;
}
