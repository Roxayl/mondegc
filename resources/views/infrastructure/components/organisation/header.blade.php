
<div id="infrastructure" class="titre-vert anchor">
  <h1>{{ $viewActionVerb }} une infrastructure<br>
    <small>
        <img src="{{ empty($infrastructure->infrastructurable->flag)
            ? url('assets/img/imagesdefaut/blason.jpg')
            : $infrastructure->infrastructurable->flag }}"
             style="height: 24px; width: 24px;">
        Organisation : {{ $infrastructure->infrastructurable->name }}
    </small>
  </h1>
</div>
