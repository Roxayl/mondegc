<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BackOfficeController extends Controller
{
    private function checkAuthorization(): void
    {
        if(! auth()->user()?->hasMinPermission('ocgc')) {
            abort(403);
        }
    }

    /**
     * @return View
     */
    public function advancedParameters(): View
    {
        $this->checkAuthorization();

        return view('back-office.advanced-parameters');
    }

    /**
     * @return RedirectResponse
     */
    public function purgeCache(): RedirectResponse
    {
        $this->checkAuthorization();

        cache()->flush();

        return redirect()->route('back-office.advanced-parameters')
            ->with('message', 'success|Le cache a été purgé avec succès.');
    }
}
