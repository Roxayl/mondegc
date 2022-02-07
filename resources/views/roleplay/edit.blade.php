
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Modifier un roleplay</h3>
@endsection

@section('modal_body')

    @include('roleplay.components.form')

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">
        <i class="icon-star icon-white"></i> Modifier le roleplay
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('roleplay.update', $roleplay) }}">
        @method('PUT')
        @csrf
        @endsection

        @section('popup_end')
    </form>
@endsection
