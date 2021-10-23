
<div class="component-block" id="organizers">
    <div class="cta-title pull-right-cta">
        <a href="#" class="btn btn-primary btn-cta component-trigger"
           {!! $getTargetHtmlAttributes(route('roleplay.manage-organizers', $roleplay), 'organizers') !!}>
            <i class="icon-white icon-pencil"></i> Modifier</a>
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
        </li>
    @endforeach
    </ul>
</div>
