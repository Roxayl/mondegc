
<ul class="breadcrumb">
    <li><a href="{{ url('back/page_pays_back.php?paysID=' . $infrastructure->infrastructurable->pays->ch_pay_id) }}"
      >Gestion du pays : {{ $infrastructure->infrastructurable->pays->ch_pay_nom }}</a>
      <span class="divider">/</span></li>
    <li><a href="{{ url('back/ville_modifier.php?ville-ID=' . $infrastructure->infrastructurable->ch_vil_ID) }}"
      >Gestion de la ville : {{ $infrastructure->infrastructurable->ch_vil_nom }}</a>
      <span class="divider">/</span></li>
    <li class="active">{{ $viewActionVerb }} une infrastructure</li>
</ul>
