@php
$viewDirectory = \App\Models\Infrastructure
                ::getUrlParameterFromMorph(get_class($infrastructure->infrastructurable));
$viewActionVerb = 'Ajouter';
@endphp

@extends('layouts.legacy')

@section('title')
    Créer une infrastructure
@endsection

@section('seodescription')
    Créez une nouvelle infrastructure.
@endsection

@section('content')

    <div class="container corps-page">
        <div class="row-fluid">

            <!-- En-tête h1 -->
            @include("infrastructure.components.$viewDirectory.header")

            <!-- Fil d'ariane -->
            @include("infrastructure.components.$viewDirectory.breadcrumb")

            <!-- Description Tempérance -->
            @include('infrastructure.components.description')

            <!-- Formulaire groupes d'infras -->
            @include('infrastructure.components.group_list')

        </div>
    </div>

@endsection