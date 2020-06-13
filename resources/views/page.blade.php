
@extends('layouts.legacy')

@section('title')
    {{$title}}
@endsection

@section('seodescription')
    {{$seo_description}}
@endsection

@section('content')

    @parent

    <div class="titre-bleu anchor">
      <h1>{{$page_title}}</h1>
    </div>

    <div class="well">
        {!!$content!!}
    </div>

@endsection