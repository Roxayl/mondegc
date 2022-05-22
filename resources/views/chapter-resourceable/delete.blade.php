
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Supprimer les ressources générées : {{ $chapterResourceable->chapter->name }}</h3>
@endsection

@section('modal_body')

    Voulez-vous vraiment supprimer les ressources générées par {{ $chapterResourceable->resourceable->getName() }}
    dans le cadre de ce chapitre ?
    <strong>Cette action est irréversible !</strong>

    <br><br>

    <img class="img-menu-drapeau" src="{{ $chapterResourceable->resourceable->getFlag() }}">
    <a href="{{ $chapterResourceable->resourceable->accessorUrl() }}">
        {{ $chapterResourceable->resourceable->getName() }}
    </a>
    <br>

    {!! \App\Services\HelperService::renderLegacyElement('temperance/resources_small', [
        'resources' => $chapterResourceable->resources()
    ]) !!}

@endsection

@section('modal_footer')
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-danger">
        <i class="icon-trash icon-white"></i> Allez, supprimer !
    </button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('chapter-resourceable.destroy', $chapterResourceable) }}">
        @method('DELETE')
        @csrf
        @endsection

        @section('popup_end')
    </form>
@endsection
