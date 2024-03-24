<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Subdivision;
use Roxayl\MondeGC\Models\SubdivisionType;
use YlsIdeas\FeatureFlags\Manager as FeatureManager;

class SubdivisionController extends Controller
{
    public function __construct(private readonly FeatureManager $featureManager)
    {
        $this->middleware(function (Request $request, \Closure $next): mixed {
            if (! $this->featureManager->accessible('subdivision')) {
                abort(404);
            }

            return $next($request);
        });
    }

    public function create(Request $request, Pays $pays): View|RedirectResponse
    {
        Gate::authorize('update', $pays);

        if ($pays->subdivisionTypes->isEmpty()) {
            return redirect()->route('pays.edit', $pays)
                ->with('message', 'error|Vous devez créer un type de subdivision administrative au préalable');
        }

        $subdivision = new Subdivision();
        $subdivision->setRelation('pays', $pays);

        $preselectedType = $request->input('subdivisionTypeId');
        $isEdit = false;

        return view(
            'subdivision.create',
            compact('subdivision', 'pays', 'preselectedType', 'isEdit')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $subdivisionType = SubdivisionType::query()->find($request->get('subdivisionType'));
        if (! $subdivisionType) {
            throw ValidationException::withMessages([
                'subdivisionTypeId' => "Ce type de subdivision n'existe pas.",
            ]);
        }
        Gate::authorize('update', $subdivisionType->pays);

        Validator::make(
            $request->all(),
            [
                'subdivisionType' => [
                    'required',
                    Rule::in($subdivisionType->pays->subdivisionTypes->pluck($subdivisionType->getKeyName())),
                ],
            ] + $this->getRules()
        )->validate();

        $subdivision = new Subdivision();
        $subdivision->fill($request->all());
        $subdivision->setRelation('subdivisionType', $subdivisionType);
        $subdivision->subdivision_type_id = $subdivisionType->getKey();
        $subdivision->save();

        return redirect()->route('pays.edit', ['pays' => $subdivision->pays])
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
        $pays = $subdivision->pays;
        Gate::authorize('update', $subdivision->pays);

        $preselectedType = $subdivision->subdivisionType->getKey();
        $isEdit = true;

        return view(
            'subdivision.edit',
            compact('subdivision', 'pays', 'preselectedType', 'isEdit')
        );
    }

    public function update(Request $request, Subdivision $subdivision): RedirectResponse
    {
        Gate::authorize('update', $subdivision->pays);

        Validator::make(
            $request->all(),
            $this->getRules()
        )->validate();

        $subdivision->fill($request->all());
        $subdivision->save();

        return redirect()->route('pays.edit', ['pays' => $subdivision->pays])
            ->with('message', 'success|Subdivision administrative créée.');
    }

    public function destroy(Subdivision $subdivision): RedirectResponse
    {
        Gate::authorize('update', $subdivision->pays);

        $subdivision->delete();

        return redirect()->route('pays.edit', ['pays' => $subdivision->pays])
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

    /**
     * @return array<string, string|array>
     */
    private function getRules(): array
    {
        return [
            'summary' => 'required|min:2|max:191',
            'content' => 'max:50000',
        ];
    }
}
