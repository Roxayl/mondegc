
@can('administrate', $organisation)
    <div class="cta-title pull-right-cta" style="margin-top: 20px;">
        <a href=" {{ url('back/communique_ajouter.php?userID='.
            auth()->user()->ch_use_id . '&cat=organisation&com_element_id=' .
            $organisation->id) }}" class="btn btn-primary btn-cta">
            <i class="icon-white icon-plus-sign"></i>
            Publier un nouveau communiqué</a>
    </div>
@endcan

<div id="actualites" class="titre-vert">
    <h1>Actualités</h1>
</div>

@if($organisation->communiques->count())
    <h3>Communiqués</h3>
    <table class="table table-hover" cellspacing="1" width="100%">
    <thead>
        <tr class="tablehead2">
            <th><i class="icon-globe"></i></th>
            <th>Titre</th>
            <th>Date de publication</th>
        </tr>
        @foreach($organisation->communiques as $communique)
        <tr>
            <td></td>
            <td>
        <a href="{{ url('page-communique.php?com_id=' . $communique->ch_com_ID) }}">
            {{ $communique->ch_com_titre }}</a>
            </td>
            <td>{{ $communique->ch_com_date->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </thead>
    </table>

@else
    <div class="alert alert-tips">
        Cette organisation n'a pas d'actualités pour le moment !
        Allez hopop, on se motive les membres !
    </div>
@endif