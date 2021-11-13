
@extends('layouts.legacy')

@section('title')
    {{ $page->title }}
@endsection

@section('seodescription')
    {{ $page->seodescription }}
@endsection

@section('content')

    @parent

    <div class="titre-bleu anchor">
      <h1>{{ $page->title }}</h1>
    </div>

    <div class="well">
        {!! $page->content !!}
    </div>

@endsection