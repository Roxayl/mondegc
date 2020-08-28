
<div id="infrastructure" class="titre-vert anchor">
  <h1>{{ $viewActionVerb }} une infrastructure<br>
    <small>
        <img src="{{ empty($infrastructure->infrastructurable->ch_vil_armoiries)
            ? url('assets/img/imagesdefaut/blason.jpg')
            : $infrastructure->infrastructurable->ch_vil_armoiries }}"
             style="height: 24px; width: 24px;">
        Ville de {{ $infrastructure->infrastructurable->ch_vil_nom }}
    </small>
  </h1>
</div>
