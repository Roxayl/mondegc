
    <div class="row-fluid">

        @php $i = 1; @endphp
        @foreach($organisation::$types as $organisationType)
            <a href="{{ route('organisation.create', ['type' => $organisationType]) }}"
                class="span6 org-container org-{{ $organisationType }}">
                <h3>{{ trans("organisation.types.$organisationType") }}</h3>
                <p>{{ trans("organisation.types.{$organisationType}-description") }}</p>
                <h4>Principes</h4>
                <ul>
                    @foreach(trans("organisation.types.{$organisationType}-criteria")
                                as $criteria)
                        <li>{{ $criteria }}</li>
                    @endforeach
                </ul>
            </a>

            @if(($i++ % 2) == 0)
                </div>
                <div class="row-fluid" style="margin-top: 20px;">
            @endif
        @endforeach

    </div>
