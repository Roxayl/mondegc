
    <li class="row-fluid"><a href="#header">
        @if(!empty($organisation->logo))
            <img src="{{$organisation->logo}}"
                 alt="Logo de {{$organisation->name}}">
        @else
            <img src="{{$organisation->flag}}"
                 alt="Drapeau de {{$organisation->name}}">
        @endif
        <p>
            <strong>{{$organisation->name}}</strong><br>
            <span class="badge org-{{ $organisation->type }}">
                {{ __("organisation.types.{$organisation->type}") }}
            </span>
        </p>
        <p><em>{{$organisation->members->count()}}
            {{ \Illuminate\Support\Str::plural('membre',
                $organisation->members->count()) }}</em></p>
        </a>
    </li>
