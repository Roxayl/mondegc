
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
            <h1>{{$page_title}}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li class="row-fluid"><img src="{{$organisation->logo}}">
                    <p><strong>{{$organisation->name}}</strong></p>
                    <p><em>{{$organisation->members->count()}} membre(s)</em></p></li>
                <li><a href="#actualites">Actualités</a></li>
                <li><a href="#presentation">Présentation</a></li>
                <li><a href="#membres">Membres</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li><a href="{{url('politique.php#organisations')}}">Organisations</a> <span class="divider">/</span></li>
                <li class="active">{{$organisation->name}}</li>
            </ul>

            <div id="actualites" class="titre-vert anchor">
                <h1>Actualités</h1>
            </div>
            <div class="well">
                <div class="alert alert-info">
                    En cours !
                </div>
            </div>

            <div id="presentation" class="titre-vert anchor">
                <h1>Présentation</h1>
            </div>
            <div class="well">
                {!!$content!!}
            </div>

            <div id="membres" class="titre-vert anchor">
                <h1>Membres</h1>
            </div>
            @foreach($organisation->members as $member)

                @include('blocks.infra_well', ['data' => [
                    'type' => 'members',
                    'overlay_text' => 'LOL',
                    'image' => $member->pays->ch_pay_lien_imgdrapeau,
                    'nom' => $member->pays->ch_pay_nom,
                    'url' => url('page-pays.php?ch_pay_id=' . $member->pays->ch_pay_id),
                    'description' => "Membre depuis le {$member->created_at->format('d/m/Y')}
                    (depuis {$member->created_at->diffInDays(\Carbon\Carbon::now())} jour(s))"
                ]])

            @endforeach

        </div>

    </div>
    </div>

@endsection