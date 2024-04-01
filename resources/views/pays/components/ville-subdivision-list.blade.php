<section>
    <div id="villes" class="titre-vert anchor">
        <h1>Subdivisions et villes</h1>
    </div>

    @foreach($pays->subdivisions as $subdivision)
        <h4 class="ml-2">
            @if($subdivision->subdivisionType)
                {{ $subdivision->subdivisionType->type_name }} :
            @endif
            {{ $subdivision->name }}</h4>
        <div class="ml-2">
            <a href="{{ route('subdivision.show', $subdivision->showRouteParameter()) }}" class="btn btn-primary">
                Visiter
            </a>
        </div>
        @if($subdivision->villes?->isNotEmpty())
            <ul class="listes listes-two-columns">
                @foreach($subdivision->villes as $ville)
                    @include('pays.components.ville-two-columns')
                @endforeach
            </ul>
        @endif
    @endforeach

    @if(($otherVilles = $villesWithoutSubdivisions())->isNotEmpty())
        @if(! empty($pays->subdivisions))
            <h4 class="ml-2">Autres villes</h4>
        @endif
        <ul class="listes listes-two-columns">
            @foreach($villesWithoutSubdivisions as $ville)
                @include('pays.components.ville-two-columns')
            @endforeach
        </ul>
    @endif
</section>
