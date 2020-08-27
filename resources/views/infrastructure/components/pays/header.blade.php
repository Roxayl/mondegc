
<div id="infrastructure" class="titre-vert anchor">
  <h1>{{ $viewActionVerb }} une infrastructure<br>
    <small>
        <img src="{{ empty($infrastructure->infrastructurable->ch_pay_lien_imgdrapeau)
            ? url('assets/img/imagesdefaut/blason.jpg')
            : $infrastructure->infrastructurable->ch_pay_lien_imgdrapeau }}"
             style="height: 24px; width: 24px;">
        Pays : {{ $infrastructure->infrastructurable->ch_pay_nom }}
    </small>
  </h1>
</div>
