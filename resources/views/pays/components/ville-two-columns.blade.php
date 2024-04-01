<li class="row-fluid">
    <div class="span5 img-listes">
        <a href="{{ url('page-ville.php?ch_ville_id=' . $ville->getKey()) }}">
            @if($ville->ch_vil_lien_img1)
                <img src="{{ $ville->ch_vil_lien_img1 }}" alt="{{ $ville->ch_vil_nom }}">
            @else
                <img src="assets/img/imagesdefaut/ville.jpg" alt="ville">
            @endif
        </a>
    </div>
    <div class="span6 info-listes" style="text-justify: none;">
        <h4 class="mb-2">{{ $ville->ch_vil_nom }}</h4>
        <p><strong>Population : </strong>{{ number_format($ville->ch_vil_population, 0, ',', '') }}
        </p>
        @if(! empty($ville->ch_vil_specialite))
            <p><strong>Spécialité : </strong> {{ $ville->ch_vil_specialite }}</p>
        @endif
        <p>Ville créée par <strong>{{ $ville->mayor->ch_use_login }}</strong></p>
        <a href="{{ url('page-ville.php?ch_ville_id=' . $ville->getKey()) }}" class="btn btn-primary">Visiter</a>
    </div>
</li>
