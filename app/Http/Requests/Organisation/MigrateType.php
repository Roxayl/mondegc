<?php

namespace App\Http\Requests\Organisation;

use App\Models\Organisation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class MigrateType extends FormRequest
{
    protected ?Organisation $organisation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function canMigrateToAlliance() : bool
    {
        $allowed = true;

        // Vérifie que l'organisation a déjà 2 infrastructures validées
        // et a au moins 2 membres.
        if($this->organisation->infrastructures->count() < 3
                || $this->organisation->members->count() < 2) {
            $allowed = false;
        }

        // Vérifie qu'aucun membre ne fait déjà partie d'une alliance.
        $members = $this->organisation->members;
        foreach($members as $member) {
            if(!empty($member->pays->alliance())) {
                $allowed = false;
                break;
            }
        }

        return $allowed;
    }

    protected function canMigrateToGroup() : bool
    {
        $allowed = true;

        // Vérifie que l'organisation n'a aucune infrastructure.
        if($this->organisation->infrastructuresAll->count() > 0) {
            $allowed = false;
        }

        return $allowed;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->organisation = Organisation::findOrFail(
            request()->route('organisation'));
        $type = request()->input('type');

        if($this->organisation->type !== Organisation::TYPE_ALLIANCE
           && $type === Organisation::TYPE_ALLIANCE) {
            if(!$this->canMigrateToAlliance()) {
                throw ValidationException::withMessages(
                    ['type' => __('organisation.validation.migrate-alliance-error')]
                );
            }
        }

        if($this->organisation->type !== Organisation::TYPE_GROUP
           && $type === Organisation::TYPE_GROUP) {
            if(!$this->canMigrateToGroup()) {
                throw ValidationException::withMessages(
                    ['type' => __('organisation.validation.migrate-group-error')]
                );
            }
        }

        $types = implode(',', Organisation::$types);
        return [
            'type' => "required|in:$types",
        ];
    }
}
