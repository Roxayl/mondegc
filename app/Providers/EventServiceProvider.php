<?php

namespace App\Providers;

use App\View\Components\Blocks\ScriptConfiguration;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use TorMorten\Eventy\Facades\Eventy;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Illuminate
        'Illuminate\Auth\Events\Registered' => [
            'Illuminate\Auth\Listeners\SendEmailVerificationNotification',
        ],

        // Infrastructure
        'App\Events\Infrastructure\InfrastructureJudged' => [
            'App\Listeners\Notification\SendInfraJudgementNotification',
            'App\Listeners\Influence\GenerateInfluence',
        ],

        // Organisation
        'App\Events\Organisation\TypeMigrated' => [
            'App\Listeners\Notification\SendTypeMigratedNotification',
            'App\Listeners\Organisation\UpdateInfrastructureInfluences',
        ],
        'App\Events\Organisation\MembershipChanged' => [
            'App\Listeners\Organisation\UpdateInfrastructureInfluences',
        ],

        // Patrimoine
        'App\Events\Patrimoine\PatrimoineCategorized' => [
            'App\Listeners\Influence\GenerateInfluence',
        ],

        // Pays
        'App\Events\Pays\MapUpdated' => [
            'App\Listeners\Influence\GenerateInfluence',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->addEventyActions();
    }

    /**
     * Ajoute les actions pour les hooks Eventy.
     *
     * @return void
     */
    private function addEventyActions(): void
    {
        // Ici, on fait afficher une balise <script> avec les infos de configuration, juste avant
        // la balise </head>.
        Eventy::addAction('display.beforeHeadClosingTag', function() {
            echo (new ScriptConfiguration)->render();
        });
    }
}
