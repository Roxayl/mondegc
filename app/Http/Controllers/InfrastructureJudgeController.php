<?php

namespace App\Http\Controllers;

use App\Events\Infrastructure\InfrastructureJudged;
use App\Models\Infrastructure;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InfrastructureJudgeController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $type = $request->has('type') ? $request->get('type') : 'pending';
        if(!in_array($type, ['pending', 'accepted', 'rejected'])) {
            throw new \InvalidArgumentException("Mauvais type de liste.");
        }

        $this->authorize('judgeInfrastructure', Infrastructure::class);

        $infrastructures = Infrastructure::$type()->paginate();

        return view('infrastructure.judge.index', compact(['infrastructures', 'type']));
    }

    /**
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $infrastructure = Infrastructure::findOrFail($id);

        $this->authorize('judgeInfrastructure', Infrastructure::class);

        return view('infrastructure.judge.show', compact(['infrastructure']));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function judge(Request $request, int $id): RedirectResponse
    {
        $infrastructure = Infrastructure::findOrFail($id);

        $this->authorize('judgeInfrastructure', Infrastructure::class);

        if(!in_array($request->input('ch_inf_statut'), [1, 2, 3]))
            throw new \InvalidArgumentException("Mauvais type de statut.");

        $infrastructure->ch_inf_statut = $request->input('ch_inf_statut');
        $infrastructure->ch_inf_juge = auth()->user()->ch_use_id;
        $infrastructure->judged_at = Carbon::now();
        if((int)$infrastructure->ch_inf_statut === Infrastructure::JUGEMENT_REJECTED) {
            $infrastructure->ch_inf_commentaire_juge =
                $request->input('ch_inf_commentaire_juge');
        }
        else {
            $infrastructure->ch_inf_commentaire_juge = null;
        }

        $infrastructure->update();

        event(new InfrastructureJudged($infrastructure));

        return redirect()->back()
            ->with('message', 'success|Infrastructure jugée avec succès !');
    }
}
