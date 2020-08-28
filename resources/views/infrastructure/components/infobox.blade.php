
<div class="span info-infrastructure-off">

    @empty($infrastructureOfficielle)
        <div class="row-fluid">
            <div class="span12"><h3>Veuillez choisir une infrastructure.</h3></div>
        </div>

    @else
        <div class="row-fluid">
          <div class="span3 img-listes img-avatar">
              <img src="{{ $infrastructureOfficielle->ch_inf_off_icone }}"
                   alt="Icône {{ $infrastructureOfficielle->ch_inf_off_nom }}">
          </div>
          <div class="span9">
            <h2>{{ $infrastructureOfficielle->ch_inf_off_nom }}</h2>
            <p>{{ $infrastructureOfficielle->ch_inf_off_desc }}</p>
          </div>
        </div>

        <h4>Influence</h4>
        <div class="row-fluid">
          <div class="span6 well icone-ressources">
            <img src="{{ url('assets/img/ressources/budget.png') }}" alt="icone Budget"><p>Budget&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_budget }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/industrie.png') }}" alt="icone Industrie"><p>Industrie&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_industrie }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/commerce.png') }}" alt="icone Commerce"><p>Commerce&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_commerce }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/agriculture.png') }}" alt="icone Agriculture"><p>Agriculture&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_agriculture }}</strong></p>
            <div class="clearfix"></div>
          </div>
          <div class="span6 well icone-ressources">
            <img src="{{ url('assets/img/ressources/tourisme.png') }}" alt="icone Tourisme"><p>Tourisme&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_tourisme }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/recherche.png') }}" alt="icone Recherche"><p>Recherche&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_recherche }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/environnement.png') }}" alt="icone Evironnement"><p>Environnement&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_environnement }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/education.png') }}" alt="icone Education"><p>Education&nbsp;: <strong>{{ $infrastructureOfficielle->ch_inf_off_education }}</strong></p>
            <div class="clearfix"></div>
          </div>
        </div>
    @endempty

</div>
