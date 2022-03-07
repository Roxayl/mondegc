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

    <div class="chapter-entry-add-media-container mb-2">
        <a href="#" class="chapter-entry-media-trigger btn btn-inverse">
            <i class="icon-picture icon-white"></i> <span>Ajouter un média</span>
        </a>
    </div>

    <div class="chapter-entry-media-container mb-4 p-3" style="display: none; border-radius: 4px; border: 1px solid grey">

        <div class="form-control media-types-container mb-2">
            <label for="media_type_none" style="display: none;">
                <input type="radio" id="media_type_none" name="media_type" value="none">
                Aucun
            </label>
            <label for="media_type_forum_post">
                <input type="radio" id="media_type_forum_post" name="media_type" value="forum_post">
                Forum / Post
            </label>
            <label for="media_type_monde_communique">
                <input type="radio" id="media_type_monde_communique" name="media_type" value="monde_communique">
                Monde GC / Communiqué
            </label>
            <label for="media_type_squirrel_squit">
                <input type="radio" id="media_type_squirrel_squit" name="media_type" value="squirrel_squit">
                Squirrel / Squit
            </label>
        </div>

        <hr>

        <div class="media-parameters-container" data-media-type="forum_post" style="display: none;">
            <div class="form-control">
                <label for="media_parameters_forum_post_url">URL du post (avec identifiant de message)</label>
                <input type="text" id="media_parameters_forum_post_url"
                       name="media_parameters[forum_post][url]"
                       class="input-large" style="width: 90%;">
                <br>
                <small class="m-0">Exemple :
                    https://www.forum-gc.com/t6557p820-joyeuses-fetes#{{ rand(200000, 250000) }}</small>
            </div>
        </div>

        <div class="media-parameters-container" data-media-type="monde_communique" style="display: none;">
            <div class="form-control">
                <label for="media_parameters_monde_communique_url">URL du communiqué</label>
                <input type="text" id="media_parameters_monde_communique_url"
                       name="media_parameters[monde_communique][url]"
                       class="input-large" style="width: 90%;">
                <br>
                <small class="m-0">Exemple : {{ url('page-communique.php?com_id=' . rand(350, 4000)) }}</small>
            </div>
        </div>

        <div class="media-parameters-container" data-media-type="squirrel_squit" style="display: none;">
            <div class="form-control">
                <label for="media_parameters_squirrel_squit_url">URL du squit</label>
                <input type="text" id="media_parameters_squirrel_squit_url"
                       name="media_parameters[squirrel_squit][url]"
                       class="input-large" style="width: 90%;">
                <br>
                <small class="m-0">Exemple : https://squirrel.roxayl.fr/squit/{{ rand(1500, 30000) }}#perm</small>
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
