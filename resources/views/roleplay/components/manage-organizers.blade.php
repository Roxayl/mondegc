
<div class="component-block" id="organizers">
    <div class="cta-title pull-right-cta">
        <a href="#" class="btn btn-primary btn-cta component-trigger"
           {!! $getTargetHtmlAttributes(route('roleplay.organizers', $roleplay), 'organizers') !!}>
            <i class="icon-white icon-pencil"></i> Retour</a>
    </div>

    <div class="titre-bleu" id="roleplay-organizers">
        <h1>Organisateurs</h1>
    </div>

    <div class="clearfix"></div>

    <ul>
    @foreach($roleplay->organizers() as $organizer)
        <li>

            <img src="{{ $organizer->getFlag() }}" alt="Drapeau de {{ $organizer->getName() }}"
                 class="img-menu-drapeau">
            <a href="{{ url($organizer->accessorUrl()) }}">
                {{ $organizer->getName() }}
            </a>

            <form action="{{ route('roleplay.remove-organizer', $roleplay) }}" method="POST" style="display: inline;">
                @method('DELETE')
                @csrf
                <input type="hidden" name="type" value="{{ $organizer->getType() }}">
                <input type="hidden" name="id" value="{{ $organizer->getKey() }}">
                <button type="submit" class="btn btn-danger">
                    <i class="icon-trash icon-white"></i>
                    Supprimer
                </button>
            </form>

        </li>
    @endforeach
    </ul>

    <div class="component-block" id="organizers-add">
        <a href="#" class="btn btn-primary component-trigger"
           {!! $getTargetHtmlAttributes(route('roleplay.add-organizer', $roleplay), 'organizers-add') !!}>
            <i class="icon-adjust icon-white"></i> Ajouter un organisateur
        </a>
    </div>
</div>
