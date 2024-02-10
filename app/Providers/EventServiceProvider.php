<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Roxayl\MondeGC\View\Components\Blocks\ScriptConfiguration;
use TorMorten\Eventy\Facades\Eventy;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Illuminate
        'Illuminate\Auth\Events\Registered' => [
            'Illuminate\Auth\Listeners\SendEmailVerificationNotification',
        ],

        // Infrastructure
        'Roxayl\MondeGC\Events\Infrastructure\InfrastructureJudged' => [
            'Roxayl\MondeGC\Listeners\Notification\SendInfraJudgementNotification',
            'Roxayl\MondeGC\Listeners\Influence\GenerateInfluence',
        ],

        // Organisation
        'Roxayl\MondeGC\Events\Organisation\TypeMigrated' => [
            'Roxayl\MondeGC\Listeners\Notification\SendTypeMigratedNotification',
            'Roxayl\MondeGC\Listeners\Organisation\UpdateInfrastructureInfluences',
        ],
        'Roxayl\MondeGC\Events\Organisation\MembershipChanged' => [
            'Roxayl\MondeGC\Listeners\Organisation\UpdateInfrastructureInfluences',
        ],

        // Patrimoine
        'Roxayl\MondeGC\Events\Patrimoine\PatrimoineCategorized' => [
            'Roxayl\MondeGC\Listeners\Influence\GenerateInfluence',
        ],

        // Pays
        'Roxayl\MondeGC\Events\Pays\MapUpdated' => [
            'Roxayl\MondeGC\Listeners\Influence\GenerateInfluence',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // On affiche une balise <script> avec les infos de configuration, juste avant
        // la balise </head>.
        Eventy::addAction('display.beforeHeadClosingTag', function(): void {
            echo (new ScriptConfiguration)->render()->toHtml();
        });
    }
}
