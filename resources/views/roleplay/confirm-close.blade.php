
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Clôturer ce roleplay : {{ $roleplay->name }}</h3>
@endsection

@section('modal_body')

    Ça y est ! Cet événement est terminé. Vous pouvez clôturer cet événement et féliciter tous les membres qui y ont
    participé. A bientôt pour un prochain roleplay !

    <div class="alert alert-info" style="margin-top: 15px;">
        Une fois ce roleplay clôturé, vous ne pourrez plus y créer de chapitres. Vous pourrez toujours modifier les
        contenus des chapitres et les récompenses qui y sont attribuées.
    </div>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-success">
        Clôturer
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('roleplay.close', $roleplay) }}">
        @csrf
        @method('PATCH')
        @endsection

        @section('popup_end')
    </form>
@endsection
