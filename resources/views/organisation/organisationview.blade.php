
@extends('layouts.legacy')

@section('title')
    {{$title}}
@endsection

@section('seodescription')
    {{$seo_description}}
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" @endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>{{$page_title}}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li class="row-fluid"><img src="ddd">
                    <p><strong>dddd</strong></p>
                    <p><em>Créé par dddd</em></p></li>
                <li><a href="#actualites">Actualités</a></li>
                <li><a href="#presentation">Présentation</a></li>
                <li><a href="#membres">Membres</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li><a href="Page-carte.php#liste-pays">Organisations</a> <span class="divider">/</span></li>
                <li class="active">{{$organisation->name}}</li>
            </ul>

            <div id="actualites" class="titre-vert anchor">
              <h1>Actualités</h1>
            </div>
            <p>En cours !</p>

            <div id="presentation" class="titre-vert anchor">
              <h1>Présentation</h1>
            </div>
            {!!$content!!}

            <div id="membres" class="titre-vert anchor">
              <h1>Membres</h1>
            </div>
            @foreach($members as $member)
                <p>{{ $member->pays->ch_pay_nom }}</p>
                <i>Membre depuis le {{$member->created_at->format('d/m/Y')}}
                    (depuis {{$member->created_at->diffInDays(\Carbon\Carbon::now())}} jour(s))
                </i>
            @endforeach

        </div>

    </div>
    </div>

@endsection