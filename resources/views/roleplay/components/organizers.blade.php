
<div class="component-block" id="organizers">
    @can('manage', $roleplay)
        <div class="cta-title pull-right-cta" style="margin-top: 7px;">
            <a href="#" class="btn btn-primary btn-cta component-trigger"
               {!! $getTargetHtmlAttributes(route('roleplay.manage-organizers', $roleplay), 'organizers') !!}>
                <i class="icon-white icon-pencil"></i> Modifier</a>
        </div>
    @endcan

    <div class="titre-bleu" id="roleplay-organizers">
        <h1>Organisateurs</h1>
    </div>

    <div class="clearfix"></div>

    <div class="well">
        <ul>
        @foreach($roleplay->organizers() as $organizer)
            <li style="margin-bottom: 3px;">
                <img src="{{ $organizer->getFlag() }}" alt="Drapeau de {{ $organizer->getName() }}"
                     class="img-menu-drapeau">
                <a href="{{ url($organizer->accessorUrl()) }}">
                    {{ $organizer->getName() }}
                </a>
                <button type="button" class="btn" style="visibility: hidden;">
                    <i class="icon-trash icon-white"></i>
                    Supprimer
                </button>
            </li>
        @endforeach
        </ul>
    </div>
</div>
