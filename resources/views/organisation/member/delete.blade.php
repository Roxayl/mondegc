
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Quitter l'organisation {{$orgMember->organisation->name}}</h3>
@endsection

@section('modal_body')

    <div class="well">
        <img class="thumb-drapeau" src="{{$orgMember->pays->ch_pay_lien_imgdrapeau}}">
        <h2 style="margin-top: -2px;">{{$orgMember->pays->ch_pay_nom}}</h2>
    </div>

    <p>Voulez-vous vraiment quitter cette organisation ?</p>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-danger">Quitter cette organisation</button>
@endsection

@section('popup_start')
    <form method="POST" action="{{route('organisation-member.destroy', ['id' => $orgMember->id])}}">
    @csrf
    @method('delete')
@endsection

@section('popup_end')
    </form>
@endsection
