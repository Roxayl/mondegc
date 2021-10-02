
@empty($rps)

    <div style="margin-left: 10px;">
        <a class="btn btn-primary" style="margin-top: 18px;"
           href="#">
            <i class="icon-bell icon-white"></i>
            Créer un événement
        </a>
    </div>

    @php return @endphp

@endempty

<div class="titre-bleu no-bg anchor">
    <div class="span6">
        <h1 style="font-size: 26px; padding-left: 16px; margin-top: 16px;">
            Événements
        </h1>
    </div>

    <div class="span6">
        <a class="btn btn-primary" style="margin-top: 18px;"
           href="#">
            <i class="icon-bell icon-white"></i>
            Créer un événement
        </a>
    </div>
</div>

<div class="clearfix"></div>

<div style="margin: 10px;">

    @foreach($rps as $rp)

        <div class="thumbnails">
            <div class="span12">
                <h4>
                    <a href="#">
                        {{ $rp->name }}
                    </a>
                </h4>

                <small style="margin: 0;">
                    @empty($rp->ending_date)
                        Événement en cours commençant le {{ $rp->starting_date->format('d/m/Y') }}
                    @else
                        Événement du {{ $rp->starting_date->format('d/m/Y') }}
                        au {{ $rp->ending_date->format('d/m/Y') }}
                    @endempty
                </small>
            </div>
        </div>

    @endforeach

</div>

<br>
<hr>
