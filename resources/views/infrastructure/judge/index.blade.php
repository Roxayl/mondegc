
@inject('helperService', 'Roxayl\MondeGC\Services\HelperService')
@inject('infrastructureClass', 'Roxayl\MondeGC\Models\Infrastructure')

@extends('layouts.legacy')

@section('title')
    Salle de jugement des infrastructures
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" @endsection

@section('styles')
<style>
.jumbotron {
    background: linear-gradient(to right, #ffe300 0%,#ff5c00 72%);
    background-size: 200%;
}
</style>
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor" id="header">
        <div class="container">
            <h1>Comité Économie</h1>
            <h2>Salle de jugement des infrastructures</h2>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li class="row-fluid"><a href="#header">
                    <p><strong>Salle de jugement des infrastructures</strong></p>
                    </a></li>
                <li><a href="#liste-infrastructures">Liste des infrastructures</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li><a href="{{url('OCGC.php')}}">OCGC</a>
                    <span class="divider">/</span></li>
                <li><a href="{{url('economie.php')}}">Économie</a>
                    <span class="divider">/</span></li>
                <li class="active">Salle de jugement des infrastructures</li>
            </ul>

            <div class="clearfix"></div>

            <div class="well">
                {!! $helperService::displayAlert() !!}
            </div>

            <h3 class="pull-left" id="liste-infrastructures">
                {!! $infrastructureClass::getJudgementTitle($type) !!}
                <span class="badge badge-info">{{ $infrastructures->total() }}</span>

                <div class="dropdown" style="display: inline;">
                    <a href="#" class="btn btn-transparent dropdown-toggle"
                       data-toggle="dropdown" style="margin-top: 20px;">
                        <i class="icon-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-mes-pays"
                        role="menu" aria-labelledby="dLabel">
                        @foreach(['pending', 'accepted', 'rejected'] as $dropdownType)
                        <li @if($dropdownType === $type) class="disabled" @endif>
                            <a href="{{ route('infrastructure-judge.index',
                                ['type' => $dropdownType]) }}">
                                {!! $infrastructureClass::getJudgementTitle($dropdownType) !!}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </h3>

            @include('infrastructure.judge.components.list')

        </div>

    </div>
    </div>

@endsection
