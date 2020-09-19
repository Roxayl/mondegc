
    <div class="well org-container org-{{ $type }}">
        <div class="org-header">
            <div class="pull-right">
                <a href="{{ route('organisation.create') }}" style="color: #d7d7d7;">
                     <small>Revenir sur mon choix...</small></a>
            </div>
            <h2>{{ __("organisation.types.$type") }}</h2>
            <p>{{ __("organisation.types.{$type}-description") }}</p>
        </div>
        <div class="org-description">
            <ul>
                @foreach(__("organisation.types.{$type}-criteria")
                            as $criteria)
                    <li>{!! $criteria !!}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <br><br>

    <form method="POST" action="{{route('organisation.store')}}">

        @include('organisation.components.form')

        <input type="hidden" name="type" value="{{ $type }}">

        <div class="control-group">
            <label for="pays_id">Choisir le propriétaire de l'organisation :</label>
            <select name="pays_id" id="pays_id">
                @foreach($pays as $thisPays):
                    <option value="{{$thisPays->ch_pay_id}}"
                        >{{$thisPays->ch_pay_nom}}</option>
                @endforeach
            </select>
        </div>

        <input type="submit" class="btn btn-primary" value="Envoyer">

    </form>
