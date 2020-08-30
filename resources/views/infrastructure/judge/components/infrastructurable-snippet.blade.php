
    @if(is_null($infrastructure->infrastructurable))

        <small style="color: red;">
            L'élément auquel appartient cette infrastructure a été supprimé.
        </small>

        @php return; @endphp

    @endif

    <small>
        @if(!empty($infrastructure->infrastructurable->getFlag()))
            <img src="{{ $infrastructure->infrastructurable->getFlag() }}"
                 alt="Drapeau : " style="height: 24px;">
        @endif

        {{ \Illuminate\Support\Str::ucfirst(
            $infrastructure->infrastructurable->getType()) }} :
        <a href="{{ $infrastructure->infrastructurable->accessorUrl() }}">
            {{ $infrastructure->infrastructurable->getName() }}
        </a>

        <!-- Afficher le pays s'il s'agit d'une ville -->
        @if($infrastructure->infrastructurable->getType() === 'ville')
            &#183; (
            <img src="{{ $infrastructure->infrastructurable->pays->getFlag() }}"
                 alt="Drapeau : " class="img-menu-drapeau">
            Pays :
            <a href="{{ $infrastructure->infrastructurable->pays->accessorUrl() }}">
                {{ $infrastructure->infrastructurable->pays->ch_pay_nom }})
            </a>
        @endif
    </small>
