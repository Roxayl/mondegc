
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Inviter un pays à rejoindre {{$organisation->name}}</h3>
@endsection

@section('modal_body')

    <p>En tant qu'administrateur de l'organisation, vous pouvez inviter un pays à rejoindre celui-ci.</p>

    <div class="control-group">
        <label for="pays_id">Inviter le pays :</label>
        <select name="pays_id" id="pays_id">
            @foreach($pays as $thisPays):
                <option value="{{$thisPays->ch_pay_id}}">{{$thisPays->ch_pay_nom}}</option>
            @endforeach
        </select>
    </div>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Inviter</button>
@endsection

@section('popup_start')
    <form method="POST" action="{{route('organisation-member.send-invitation',
                                    ['organisation_id' => $organisation->id])}}">
    @csrf
@endsection

@section('popup_end')
    </form>
@endsection
