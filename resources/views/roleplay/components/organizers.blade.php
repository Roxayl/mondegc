
<div>
    <h3>Organisateurs</h3>

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
