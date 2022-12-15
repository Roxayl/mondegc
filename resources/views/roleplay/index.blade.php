
@inject('helperService', 'App\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    Liste des roleplays
@endsection

@section('content')

    @parent

    <div class="container corps-page">
        <div class="row-fluid">
            <div class="titre-bleu">
                <h1>Liste des roleplays</h1>
            </div>

            @foreach($roleplays as $roleplay)
                {{ $roleplay->name }}
            @endforeach
        </div>
    </div>

@endsection
