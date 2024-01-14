<ul class="breadcrumb">
    <li>
        <a href="{{ url('Page-carte.php') }}">Pays</a>
        <span class="divider">/</span>
    </li>
    <li>
        <a href="{{ url('page-pays.php?ch_pay_id=' . $ville->pays->ch_pay_id) }}">{{ $ville->pays->ch_pay_nom }}</a>
        <span class="divider">/</span>
    </li>
    <li>
        <a href="{{ url('page-pays.php?ch_pay_id=' . $ville->pays->ch_pay_id) }}#villes">Villes</a>
        <span class="divider">/</span>
    </li>
    <li>
        <a href="{{ url('page-ville.php?ch_ville_id=' . $ville->ch_vil_ID) . '&ch_pay_id=' . $ville->ch_vil_paysID }}">
            {{ $ville->ch_vil_nom }}</a>
        <span class="divider">/</span>
    </li>
    <li class="active">
        Historique
    </li>
</ul>
