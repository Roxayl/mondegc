
<div class="span info-infrastructure-off">

    @empty($infrastructureOfficielle)
        <div class="row-fluid">
            <div class="span12"><h3>Veuillez choisir une infrastructure.</h3></div>
        </div>

    @else
        @php $thisResources = $infrastructureOfficielle->mapResources(); @endphp

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

        <h4>Influence de base</h4>
        <div class="row-fluid">
          <div class="span6 well icone-ressources">
            <img src="{{ url('assets/img/ressources/budget.png') }}" alt="icone Budget"><p>Budget&nbsp;: <strong>{{ $thisResources['budget'] }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/industrie.png') }}" alt="icone Industrie"><p>Industrie&nbsp;: <strong>{{ $thisResources['industrie'] }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/commerce.png') }}" alt="icone Commerce"><p>Commerce&nbsp;: <strong>{{ $thisResources['commerce'] }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/agriculture.png') }}" alt="icone Agriculture"><p>Agriculture&nbsp;: <strong>{{ $thisResources['agriculture'] }}</strong></p>
            <div class="clearfix"></div>
          </div>
          <div class="span6 well icone-ressources">
            <img src="{{ url('assets/img/ressources/tourisme.png') }}" alt="icone Tourisme"><p>Tourisme&nbsp;: <strong>{{ $thisResources['tourisme'] }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/recherche.png') }}" alt="icone Recherche"><p>Recherche&nbsp;: <strong>{{ $thisResources['recherche'] }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/environnement.png') }}" alt="icone Evironnement"><p>Environnement&nbsp;: <strong>{{ $thisResources['environnement'] }}</strong></p>
            <div class="clearfix"></div>
            <img src="{{ url('assets/img/ressources/education.png') }}" alt="icone Education"><p>Education&nbsp;: <strong>{{ $thisResources['education'] }}</strong></p>
            <div class="clearfix"></div>
          </div>
        </div>
    @endempty

</div>
