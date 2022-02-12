
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Supprimer le chapitre : {{ $chapter->name }}</h3>
@endsection

@section('modal_body')

    Voulez-vous vraiment supprimer ce chapitre ?
    <strong>Cette action est irréversible !</strong>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-danger">
        <i class="icon-trash icon-white"></i> Allez, supprimer !
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('chapter.destroy', $chapter) }}">
        @method('DELETE')
        @csrf
        @endsection

        @section('popup_end')
    </form>
@endsection
