<?php

namespace Roxayl\MondeGC\Providers;

use Roxayl\MondeGC\Models;
use Roxayl\MondeGC\Policies;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'Model\Model::class' => Policies\ModelPolicy::class,
        Models\Chapter::class => Policies\ChapterPolicy::class,
        Models\ChapterEntry::class => Policies\ChapterEntryPolicy::class,
        Models\ChapterResourceable::class => Policies\ChapterResourceablePolicy::class,
        Models\Infrastructure::class => Policies\InfrastructurePolicy::class,
        Models\Organisation::class => Policies\OrganisationPolicy::class,
        Models\OrganisationMember::class => Policies\OrganisationMemberPolicy::class,
        Models\Patrimoine::class => Policies\PatrimoinePolicy::class,
        Models\Pays::class => Policies\PaysPolicy::class,
        Models\Roleplay::class => Policies\RoleplayPolicy::class,
        Models\Ville::class => Policies\VillePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
