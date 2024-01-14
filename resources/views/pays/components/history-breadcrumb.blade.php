<ul class="breadcrumb">
    <li><a href="{{ url('Page-carte.php') }}">Pays</a> <span class="divider">/</span></li>
    <li><a href="{{ url('page-pays.php?ch_pay_id=' . $pays->ch_pay_id) }}">{{ $pays->ch_pay_nom }}</a>
        <span class="divider">/</span></li>
    <li class="active">Historique</li>
</ul>
