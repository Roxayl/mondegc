
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Rejoindre l'organisation {{$organisation->name}}</h3>
@endsection

@section('modal_body')

    <p>Une demande d'accès sera formulée au nom du pays choisi. Elle devra être acceptée
       par un administrateur de l'organisation.</p>

    <div class="control-group">
        <label for="pays_id">Rejoindre en tant que :</label>
        <select name="pays_id" id="pays_id">
            @foreach($pays as $thisPays):
                <option value="{{$thisPays->ch_pay_id}}">{{$thisPays->ch_pay_nom}}</option>
            @endforeach
        </select>
    </div>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
@endsection

@section('popup_start')
    <form method="POST" action="{{route('organisation-member.store', ['organisationId' => $organisation->id])}}">
    @csrf
@endsection

@section('popup_end')
    </form>
@endsection
