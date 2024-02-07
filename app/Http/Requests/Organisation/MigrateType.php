<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Requests\Organisation;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Roxayl\MondeGC\Models\Organisation;

class MigrateType extends FormRequest
{
    protected ?Organisation $organisation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function canMigrateToAgency(): bool
    {
        $allowed = true;

        if(! auth()->check() || ! auth()->user()->hasMinPermission('admin')) {
            $allowed = false;
        }

        return $allowed;
    }

    protected function canMigrateToAlliance(): bool
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
            if(! empty($member->pays->alliance())) {
                $allowed = false;
                break;
            }
        }

        return $allowed;
    }

    protected function canMigrateToGroup(): bool
    {
        $allowed = true;

        // Vérifie que l'organisation n'a aucune infrastructure.
        if($this->organisation->infrastructuresAll->count() > 0) {
            $allowed = false;
        }

        return $allowed;
    }

    protected function canMigrateBasedOnTime(): bool
    {
        $allowed = true;

        // Empêche la migration si l'organisation a déjà changé de type au cours des
        // sept derniers jours.
        if(! is_null($this->organisation->type_migrated_at)) {
            if($this->organisation->type_migrated_at > Carbon::now()->subDays(7)) {
                $allowed = false;
            }
        }

        return $allowed;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws ValidationException
     */
    public function rules(): array
    {
        $this->organisation = Organisation::query()->findOrFail(request()->route('organisation'));
        $type = request()->input('type');

        $errors = [];

        if($this->organisation->type !== Organisation::TYPE_ALLIANCE
           && $type === Organisation::TYPE_ALLIANCE) {
            if(!$this->canMigrateToAlliance()) {
                $errors['type'] = __('organisation.validation.migrate-alliance-error');
            }
        }

        if($this->organisation->type !== Organisation::TYPE_GROUP
           && $type === Organisation::TYPE_GROUP) {
            if(!$this->canMigrateToGroup()) {
                $errors['type'] = __('organisation.validation.migrate-group-error');
            }
        }

        if($this->organisation->type === Organisation::TYPE_AGENCY
           || $type === Organisation::TYPE_AGENCY) {
            if(!$this->canMigrateToAgency()) {
                $errors['type'] = __('organisation.validation.migrate-agency-error');
            }
        }

        if(! $this->canMigrateBasedOnTime()) {
            $errors['early'] =  __('organisation.validation.migrate-too-early-error');
        }

        if(count($errors)) {
            throw ValidationException::withMessages($errors);
        }

        $types = implode(',', Organisation::$types);
        return [
            'type' => "required|in:$types",
        ];
    }
}
