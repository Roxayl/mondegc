<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Roxayl\MondeGC\Models\Subdivision;
use Roxayl\MondeGC\Models\SubdivisionType;
use YlsIdeas\FeatureFlags\Manager as FeatureManager;

class SubdivisionController extends Controller
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

    public function create(): View
    {
        $subdivision = new Subdivision();

        return view('subdivision.create', compact('subdivision'));
    }

    public function store(Request $request): RedirectResponse
    {
        $subdivisionType = SubdivisionType::query()->findOrFail($request->get('subdivisionTypeId'));
        Gate::authorize('update', $subdivisionType->pays);

        $subdivision = new Subdivision();
        $subdivision->fill($request->all());
        $subdivision->setRelation('subdivisionType', $subdivisionType);
        $subdivision->subdivision_type_id = $subdivisionType->getKey();
        $subdivision->save();

        return redirect('pays.show', $subdivision->pays->showRouteParameter())
            ->with('message', 'success|Subdivision administrative créée.');
    }

    public function show(
        int $paysId,
        string $paysSlug,
        string $subdivisionTypeName,
        Subdivision $subdivision,
        string $subdivisionSlug
    ): View|RedirectResponse {
        $routeParameter = $subdivision->showRouteParameter();

        if (
            $paysId !== $routeParameter['paysId']
            || $paysSlug !== $routeParameter['paysSlug']
            || $subdivisionTypeName !== $routeParameter['subdivisionTypeName']
            || $subdivisionSlug !== $routeParameter['subdivisionSlug']
        ) {
            return to_route('subdivision.show', $routeParameter);
        }

        if (! $this->featureEnabled($subdivision)) {
            abort(404);
        }

        return view('subdivision.show', compact('subdivision'));
    }

    public function edit(Subdivision $subdivision): View
    {
        Gate::authorize('update', $subdivision->pays);

        return view('subdivision.edit', compact('subdivision'));
    }

    public function update(Request $request, Subdivision $subdivision): RedirectResponse
    {
        $subdivisionType = SubdivisionType::query()->findOrFail($request->get('subdivisionTypeId'));
        Gate::authorize('update', $subdivisionType->pays);

        $subdivision->fill($request->all());
        $subdivision->setRelation('subdivisionType', $subdivisionType);
        $subdivision->subdivision_type_id = $subdivisionType->getKey();
        $subdivision->save();

        return redirect('pays.show', $subdivision->pays->showRouteParameter())
            ->with('message', 'success|Subdivision administrative créée.');
    }

    public function destroy(Subdivision $subdivision): RedirectResponse
    {
        Gate::authorize('update', $subdivision->pays);

        $subdivision->delete();

        return redirect('pays.show', $subdivision->pays->showRouteParameter())
            ->with('message', 'success|Subdivision administrative supprimée.');
    }

    /**
     * @param  Subdivision  $subdivision
     * @return bool
     */
    private function featureEnabled(Subdivision $subdivision): bool
    {
        return (bool) $subdivision->pays?->use_subdivisions;
    }
}
