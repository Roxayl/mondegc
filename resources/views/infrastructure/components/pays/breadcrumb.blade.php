
<ul class="breadcrumb">
    <li><a href="{{ url('back/page_pays_back.php?paysID=' . $infrastructure->infrastructurable->ch_pay_id) }}"
        >Gestion du pays : {{ $infrastructure->infrastructurable->ch_pay_nom }}</a>
      <span class="divider">/</span></li>
    <li class="active">{{ $viewActionVerb }} une infrastructure</li>
</ul>
