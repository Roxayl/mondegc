<ul class="breadcrumb">
    <li><a href="{{ url('politique.php#organisations') }}">Organisations</a> <span class="divider">/</span></li>
    <li><a href="{{ route('organisation.showslug', $organisation->showRouteParameter()) }}">{{ $organisation->name }}</a>
        <span class="divider">/</span></li>
    <li class="active">Historique</li>
</ul>
