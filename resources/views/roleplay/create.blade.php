
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Créer un nouveau roleplay</h3>
@endsection

@section('modal_body')

    <p>Vous pouvez créer un nouveau roleplay pour centraliser les événements qui s'y déroulent !</p>
    <p>Votre événement est séquencé en <strong>chapitres</strong>.</p>

    @include('roleplay.components.form')

    <div class="control-group">
        <label class="control-label">Organisateur</label>
        <x-blocks.roleplayable-selector />
    </div>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">
        <i class="icon-star icon-white"></i> Lancer le roleplay
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('roleplay.store') }}">
        @csrf
@endsection

@section('popup_end')
    </form>
@endsection
