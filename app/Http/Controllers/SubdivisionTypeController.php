<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\SubdivisionType;
use YlsIdeas\FeatureFlags\Manager as FeatureManager;

class SubdivisionTypeController extends Controller
{
    public function __construct(private readonly FeatureManager $featureManager)
    {
        $this->middleware(function (Request $request, \Closure $next) {
            if (! $this->featureManager->accessible('subdivision')) {
                abort(404);
            }

            return $next($request);
        });
    }

    public function create(Request $request, Pays $pays): View
    {
        Gate::authorize('update', $pays);

        $subdivisionType = new SubdivisionType();
        $subdivisionType->setRelation('pays', $pays);
        $subdivisionType->pays_id = $pays->getKey();

        return view('subdivision-type.create', compact('subdivisionType'));
    }

    public function store(Request $request, Pays $pays): RedirectResponse
    {
        Gate::authorize('update', $pays);

        $subdivisionType = new SubdivisionType();
        $subdivisionType->fill($request->all());
        $subdivisionType->setRelation('pays', $pays);
        $subdivisionType->pays_id = $pays->getKey();
        $subdivisionType->save();

        return redirect('pays.show', $pays->showRouteParameter())
            ->with('message', 'success|Type de subdivision administrative créée.');
    }

    public function edit(SubdivisionType $subdivisionType): View
    {
        Gate::authorize('update', $subdivisionType->pays);

        return view('subdivision-type.edit', compact('subdivisionType'));
    }

    public function update(Request $request, SubdivisionType $subdivisionType): RedirectResponse
    {
        $pays = Pays::query()->findOrFail($subdivisionType->pays_id);
        Gate::authorize('update', $pays);

        $subdivisionType = new SubdivisionType();
        $subdivisionType->fill($request->all());
        $subdivisionType->save();

        return redirect('pays.show', $pays->showRouteParameter())
            ->with('message', 'success|Type de subdivision administrative mise à jour.');
    }

    public function destroy(SubdivisionType $subdivisionType): RedirectResponse
    {
        $pays = Pays::query()->findOrFail($subdivisionType->pays_id);
        Gate::authorize('update', $pays);
        $subdivisionType->delete();

        return redirect('pays.show', $pays->showRouteParameter())
            ->with('message', 'success|Type de subdivision administrative supprimée.');
    }

    /**
     * @param  SubdivisionType  $subdivisionType
     * @return bool
     */
    private function featureEnabled(SubdivisionType $subdivisionType): bool
    {
        return (bool) $subdivisionType->pays?->use_subdivisions;
    }
}
