
@extends('layouts.legacy')

@section('title')
    {{$title}}
@endsection

@section('seodescription')
    {{$seo_description}}
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>{{$page_title}}</h1>
        </div>
    </header>

    <div class="well">
        {!!$content!!}

        <h3>Membres</h3>
        @foreach($members as $member)
            <p>{{ $member->pays->ch_pay_nom }}</p>
            <i>Membre depuis le {{$member->created_at->format('d/m/Y')}}
                (depuis {{$member->created_at->diffInDays(\Carbon\Carbon::now())}} jour(s))
            </i>
        @endforeach
    </div>

@endsection