
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Modifier les permissions d'un pays de {{$orgMember->organisation->name}}</h3>
@endsection

@section('modal_body')

    <div class="well">
        <img class="thumb-drapeau" src="{{$orgMember->pays->ch_pay_lien_imgdrapeau}}">
        <h2 style="margin-top: -2px;">{{$orgMember->pays->ch_pay_nom}}</h2>
    </div>

    <div class="control-group">
        <label for="permissions">Droits d'accès :</label>
        <select name="permissions" id="permissions">
            <option
                {{ (old("permissions") == \App\Models\Organisation::$permissions['administrator'] ? "selected":"") }}
                value="{{\App\Models\Organisation::$permissions['administrator']}}"
                    >Administrateur</option>
            <option
                {{ (old("permissions") == \App\Models\Organisation::$permissions['member'] ? "selected":"") }}
                value="{{\App\Models\Organisation::$permissions['member']}}"
                    >Membre</option>
        </select>
    </div>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
@endsection

@section('popup_start')
    <form method="POST" action="{{route('organisation-member.update', ['id' => $orgMember->id])}}">
    @csrf
@endsection

@section('popup_end')
    </form>
@endsection
