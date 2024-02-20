<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Roxayl\MondeGC\Events\Infrastructure\InfrastructureJudged;
use Roxayl\MondeGC\Models\Infrastructure;

class InfrastructureJudgeController extends Controller
{
    /**
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $type = $request->input('type') ?? 'pending';
        if (! in_array($type, ['pending', 'accepted', 'rejected'], true)) {
            throw new \InvalidArgumentException('Mauvais type de liste.');
        }

        $this->authorize('judgeInfrastructure', Infrastructure::class);

        $infrastructures = Infrastructure::$type()->paginate();

        return view('infrastructure.judge.index', compact(['infrastructures', 'type']));
    }

    /**
     * @param  int  $id
     * @return View
     */
    public function show(int $id): View
    {
        $infrastructure = Infrastructure::query()->findOrFail($id);

        $this->authorize('judgeInfrastructure', Infrastructure::class);

        return view('infrastructure.judge.show', compact(['infrastructure']));
    }

    /**
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function judge(Request $request, int $id): RedirectResponse
    {
        $infrastructure = Infrastructure::query()->findOrFail($id);

        $this->authorize('judgeInfrastructure', Infrastructure::class);

        if (! in_array((int) $request->input('ch_inf_statut'), [1, 2, 3], true)) {
            throw new \InvalidArgumentException('Mauvais type de statut.');
        }

        $infrastructure->ch_inf_statut = (int) $request->input('ch_inf_statut');
        $infrastructure->ch_inf_juge = auth()->user()->getAuthIdentifier();
        $infrastructure->judged_at = Carbon::now();
        if ($infrastructure->ch_inf_statut === Infrastructure::JUGEMENT_REJECTED) {
            $infrastructure->ch_inf_commentaire_juge = $request->input('ch_inf_commentaire_juge');
        } else {
            $infrastructure->ch_inf_commentaire_juge = null;
        }

        $infrastructure->update();

        event(new InfrastructureJudged($infrastructure));

        return redirect()->back()->with('message', 'success|Infrastructure jugée avec succès !');
    }
}
