
<ul class="breadcrumb">
    <li><a href="{{url('politique.php#organisations')}}">Organisations</a> <span class="divider">/</span></li>
    <li><a href="{{ route('organisation.showslug',
                     $infrastructure->infrastructurable->showRouteParameter()) }}"
      >{{ $infrastructure->infrastructurable->name }}</a>
      <span class="divider">/</span></li>
    <li class="active">{{ $viewActionVerb }} une infrastructure</li>
</ul>
