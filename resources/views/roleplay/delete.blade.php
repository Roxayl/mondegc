
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Supprimer le roleplay : {{ $roleplay->name }}</h3>
@endsection

@section('modal_body')

    Voulez-vous vraiment supprimer ce roleplay ?

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-danger">
        <i class="icon-trash icon-white"></i> Allez, supprimer !
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('roleplay.destroy', $roleplay) }}">
        @method('DELETE')
        @csrf
        @endsection

        @section('popup_end')
    </form>
@endsection
