
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Supprimer l'infrastructure {{ $infrastructure->nom_infra }}</h3>
@endsection

@section('modal_body')

    <p>Voulez-vous vraiment supprimer l'infrastructure suivante ?</p>

    <div class="row-fluid">
        <div class="span3">
            <img src="{{ $infrastructure->ch_inf_lien_image }}"
                 alt="Image de l'infrastructure">
        </div>
        <div class="span9">
            <h3>{{ $infrastructure->nom_infra }}</h3>
            <p>{{ $infrastructure->ch_inf_commentaire }}</p>
        </div>
    </div>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-danger">
        <i class="icon-trash icon-white"></i>
        Supprimer
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{route('infrastructure.destroy',
        ['infrastructure_id' => $infrastructure->ch_inf_id])}}">
    @csrf
    @method('DELETE')
@endsection

@section('popup_end')
    </form>
@endsection
