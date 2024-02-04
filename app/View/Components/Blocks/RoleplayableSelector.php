<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Blocks;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Component;
use Illuminate\View\View;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\Factories\RoleplayableFactory;

class RoleplayableSelector extends Component
{
    public ?Roleplayable $roleplayable;

    public string $formId;

    public string $endpointUrl;

    public const defaultRouteEndpoint = 'roleplay.roleplayables';

    /**
     * @param  Roleplayable|null  $roleplayable
     * @param  string|null  $endpointUrl
     */
    public function __construct(?Roleplayable $roleplayable = null, string $endpointUrl = null)
    {
        $this->roleplayable = $roleplayable;

        $this->formId = uniqid();

        if($endpointUrl === null) {
            $endpointUrl = route(self::defaultRouteEndpoint);
        }
        $this->endpointUrl = $endpointUrl;
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('blocks.roleplayable-selector');
    }

    /**
     * Créé une instance d'un roleplayable à partir des paramètre passés dans la requête {@see Request}.
     * Il est nécessaire que les champs "type" et "id" soient présents dans les paramètres de la requête.
     *
     * @param Request $request
     * @return Roleplayable
     * @throws ValidationException
     */
    public static function createRoleplayableFromForm(Request $request)
    {
        $form = $request->all(['type', 'id']);

        /** @var Roleplayable|null $roleplayable */
        $roleplayable = RoleplayableFactory::find($form['type'], (int) $form['id']);

        if($roleplayable === null) {
            throw ValidationException::withMessages(["Ce roleplayable n'existe pas."]);
        }

        return $roleplayable;
    }
}
