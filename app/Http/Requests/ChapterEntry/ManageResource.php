<?php

namespace Roxayl\MondeGC\Http\Requests\ChapterEntry;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Roxayl\MondeGC\Models\ChapterEntry;

class ManageResource extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(ChapterEntry $entry): bool
    {
        return true;
    }

    /**
     * Définit le média dans une instance de {@see ChapterEntry} donné, en fonction des données passées via le
     * formulaire.
     *
     * @param  ChapterEntry  $entry
     * @return void
     */
    public function setMediaFromRequest(ChapterEntry $entry): void
    {
        $mediaType = $this->input('media_type');

        if(! $this->exists('media_type') || $mediaType === 'none') {
            $entry->media_type = null;
            $entry->media_parameters = null;
            $entry->media_data = null;
            return;
        }

        $entry->media_type = str_replace('_', '.', $mediaType);

        if(! array_key_exists($entry->media_type, ChapterEntry::getComponentMorphMap())) {
            throw ValidationException::withMessages(["Ce type de média n'existe pas."]);
        }

        $entry->media_parameters = $this->input('media_parameters')[$mediaType];
        $entry->generateMediaData($entry->media_parameters);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content' => ['min:2', 'required'],
        ];
    }
}
