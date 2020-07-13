
@extends('layouts.legacy')

@section('title')
    {{$title}}
@endsection

@section('seodescription')
    {{$seo_description}}
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" @endsection

@section('styles')
    .jumbotron {
        background-image: url('{{$organisation->flag}}');
    }
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>{{$organisation->name}}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li class="row-fluid"><img src="{{$organisation->logo}}">
                    <p><strong>{{$organisation->name}}</strong></p>
                    <p><em>{{$organisation->members->count()}} membre(s)</em></p></li>
                <li><a href="#modifier">Modifier</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li><a href="{{url('politique.php#organisations')}}">Organisations</a>
                    <span class="divider">/</span></li>
                <li>
                    <a href="{{route('organisation.showslug',
                        ['id' => $organisation->id,
                         'slug' => $organisation->slug()])}}"
                        >{{$organisation->name}}</a>
                    <span class="divider">/</span></li>
                <li class="active">Modifier</li>
            </ul>

            <div id="actualites" class="titre-vert anchor">
                <h1>Modifier une organisation : {{$organisation->name}}</h1>
            </div>
            <div class="well">
                <div class="alert alert-info">
                    En cours !
                </div>
            </div>

        </div>

    </div>
    </div>

@endsection