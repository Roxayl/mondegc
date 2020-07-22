
@extends('layouts.legacy')

@section('title', 'Test de layout')

@section('content')

    @parent

    <p>Body content.</p>

    <p>Ceci est une page dont le contenu est écrit dans layouttester.blade.php.</p>

@endsection