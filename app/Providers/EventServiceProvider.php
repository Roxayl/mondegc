<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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

        //
    }
}
