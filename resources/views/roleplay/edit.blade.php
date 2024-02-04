
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Modifier un roleplay</h3>
@endsection

@section('modal_body')
    <h4>Modifier</h4>

    @include('roleplay.components.form')

    <button type="submit" class="btn btn-primary">
        <i class="icon-star icon-white"></i> Modifier le roleplay
    </button>

    <hr style="margin-top: 20px;">

    <h4>Gestion</h4>

    <div class="alert alert-block alert-warning" style="margin-top: 20px;">
        <h4>Terminer l'événement</h4>
        <a href="{{ route('roleplay.confirm-close', $roleplay) }}" class="btn btn-warning"
           data-toggle="modal" data-target="#modal-container-small">
            Clôturer ce roleplay
        </a>
    </div>

    <div class="alert alert-block alert-danger" style="margin-top: 20px;">
        <h4>Zone de danger</h4>
        <a href="{{ route('roleplay.delete', $roleplay) }}" class="btn btn-danger"
           data-toggle="modal" data-target="#modal-container">
            <i class="icon-trash icon-white"></i> Supprimer le roleplay...
        </a>
    </div>

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>

    <script type="text/javascript">
        /** Modal **/
        $("a[data-toggle=modal]").click(function (e) {
            var lv_target = $(this).attr('data-target');
            var lv_url = $(this).attr('href');
            $(lv_target).load(lv_url);
        });
    </script>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('roleplay.update', $roleplay) }}">
        @method('PUT')
        @csrf
        @endsection

        @section('popup_end')
    </form>
@endsection
