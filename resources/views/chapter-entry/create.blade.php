<form method="POST" action="{{ route('chapter-entry.store', $chapter) }}">
    @csrf

    <h4 class="mt-0">
        Ajouter une actualité au chapitre {{ $chapter->name }}
        (<a href="#" class="component-trigger"
                {!! $getTargetHtmlAttributes(route('chapter-entry.create-button', $chapter),
                    'chapter-entry-add-container-' . $chapter->identifier) !!}
        >annuler</a>)
    </h4>

    <div class="form-control">
        <label>
            Contenu
            <textarea style="width: 100%;" rows="10" name="content"></textarea>
        </label>
    </div>

    <div class="chapter-entry-add-media-container">
        <a href="#" class="chapter-entry-media-trigger">
            <i class="icon-picture"></i> Ajouter un média
        </a>
    </div>

    <div class="chapter-entry-media-container" style="display: none;">

        <div class="form-control media-types-container">
            <label for="media_type_none" style="display: none;">Aucun
                <input type="radio" id="media_type_none" name="media_type" value="none">
            </label>
            <label for="media_type_squirrel_squit">Squirrel / Squit
                <input type="radio" id="media_type_squirrel_squit" name="media_type" value="squirrel_squit">
            </label>
            <label for="media_type_monde_communique">Monde GC / Communiqué
                <input type="radio" id="media_type_monde_communique" name="media_type" value="monde_communique">
            </label>
        </div>

        <div class="form-control media-parameters-container" data-media-type="squirrel_squit" style="display: none;">
            <div class="form-control">
                <label for="media_parameters_squirrel_squit_url">URL du squit</label>
                <input type="text" id="media_parameters_squirrel_squit_url" name="media_parameters[squirrel_squit][url]"
                       class="input-large">
            </div>
        </div>

        <div class="form-control media-parameters-container" data-media-type="monde_communique" style="display: none;">
            <div class="form-control">
                <label for="media_parameters_monde_communique_url">URL du communiqué</label>
                <input type="text" id="media_parameters_monde_communique_url" name="media_parameters[monde_communique][url]"
                       class="input-large">
            </div>
        </div>

    </div>

    <div class="form-control pull- mb-3">
        <button type="submit" class="btn btn-primary">
            Publier
        </button>
    </div>
</form>

<hr>
