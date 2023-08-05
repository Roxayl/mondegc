
<div class="well" id="thumbnail-infra-container">
    <ul class="thumbnails">

    @foreach($infrastructure_groupes as $key => $group)

        <li class="span4">
          <div class="thumbnail">
            <img src="{{ $group->url_image }}" alt="Illustration {{ $group->nom_groupe }}">
            <h3>{{ $group->nom_groupe }}</h3>
            <form action=" {{ route('infrastructure.create', [
                    'infrastructurableType' => $infrastructure::getUrlParameterFromMorph($infrastructure->infrastructurable_type),
                    'infrastructurableId' => $infrastructure->infrastructurable_id,
                ]) }}" method="GET">
                <input name="infrastructure_groupe_id" type="hidden"
                   value="{{ $group->id }}">
            <button class="btn btn-primary btn-margin-left" type="submit">Choisir...</button>
            </form>
          </div>
        </li>

    @if(($key + 1) % 3 === 0)
    </ul>
    <ul class="thumbnails">
    @endif

    @endforeach

    </ul>
</div>