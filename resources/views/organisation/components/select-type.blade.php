
    <div class="row-fluid">
        <div class="span2">
            <img src="http://vasel.yt/wiki/images/d/da/Icone-globe-cpgc.png"
                 alt="Icône d'organisation CPGC">
        </div>
        <div class="span10">
            <h3 style="margin: 10px 0;">L'union fait la force !</h3>
            <p>@lang('organisation.create.create-description')</p>
            <p><a class="guide-link"
          href="http://vasel.yt/wiki/index.php?title=GO/Organisations_internationales#Cr.C3.A9er_une_organisation"
        >Quel type d'organisation pour moi ? GO!</a></p>
        </div>
    </div>

    <br><br>

    @foreach($organisation::$types as $organisationType)
        <div class="row-fluid">
        <div class="span12 org-container" style="margin-bottom: 15px;">

            <div class="org-header org-{{ $organisationType }}">
                <h2>{{ __("organisation.types.$organisationType") }}</h2>
                <p>{{ __("organisation.types.{$organisationType}-description") }}
                </p>
            </div>

            <div class="org-description">
                <ul>
                    @foreach(__("organisation.types.{$organisationType}-criteria")
                                as $criteria)
                        <li>{!! $criteria !!}</li>
                    @endforeach
                </ul>

                @if(in_array($organisationType, $organisation::$typesCreatable))
                    <a class="btn btn-primary" href="{{ route('organisation.create',
                             ['type' => $organisationType]) }}"
                       style="margin: 0 12px 12px;">
                        Créer une {{ __("organisation.types.$organisationType") }}</a>

                @elseif($organisationType === $organisation::TYPE_ALLIANCE)
                    <small style="display: block;"><i class="icon-info-sign"></i>
                        {!! __("organisation.create.create-alliance-description") !!}
                    </small>

                @else
                    <small><i class="icon-info-sign"></i>
                        {!! __("organisation.create.create-unable") !!}
                    </small>
                @endif
            </div>

        </div>
        </div>
    @endforeach
