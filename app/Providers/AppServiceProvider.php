<?php

namespace App\Providers;

use App\Models\OcgcProposal;
use App\Models\Tools\ProposalResults;
use CondorcetPHP\Condorcet\Election;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Reliese\Coders\CodersServiceProvider;
use YlsIdeas\FeatureFlags\Facades\Features;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment() === 'local') {
            $this->app->register(CodersServiceProvider::class);
        }

        $this->app->bind(ProposalResults::class, function(Application $application, array $parameters) {
            /** @var OcgcProposal|int $proposal */
            $proposal = $parameters[0];

            if(is_numeric($proposal)) {
                $proposal = OcgcProposal::find($proposal);
            }

            if(!($proposal instanceof OcgcProposal)) {
                throw new \InvalidArgumentException("Parameters is not an instance of " . OcgcProposal::class);
            }

            return new ProposalResults(new Election(), $proposal);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('blocks.pagination.bootstrap-2');

        Features::noScheduling();
    }
}
