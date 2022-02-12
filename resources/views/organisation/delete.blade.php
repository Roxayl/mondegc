
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Supprimer l'organisation : {{ $organisation->name }}</h3>
@endsection

@section('modal_body')

    <p>Voulez-vous vraiment supprimer cette organisation ?</p>

    <p>Les membres perdront les bienfaits qu'ils bénéficient à travers cette organisation. En particulier, les
       infrastructures éventuelles de cette organisation ne généreront plus de ressources.</p>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-danger">
        <i class="icon-trash icon-white"></i> Allez, supprimer !
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('organisation.destroy', $organisation) }}">
        @method('DELETE')
        @csrf
        @endsection

        @section('popup_end')
    </form>
@endsection
