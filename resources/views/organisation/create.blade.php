
@extends('layouts.legacy')

@section('title')
    Créer une organisation
@endsection

@section('seodescription')
    Créer une nouvelle organisation de pays au sein du Monde GC.
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" @endsection

@section('styles')
<link href="{{url('SpryAssets/SpryValidationTextField.css')}}" rel="stylesheet" type="text/css">
<link href="{{url('SpryAssets/SpryValidationTextarea.css')}}" rel="stylesheet" type="text/css">
<link href="{{url('SpryAssets/SpryValidationRadio.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('scripts')
<!-- SPRY ASSETS -->
<script src="{{url('SpryAssets/SpryValidationTextField.js')}}" type="text/javascript"></script>
<script src="{{url('SpryAssets/SpryValidationTextarea.js')}}" type="text/javascript"></script>
<script src="{{url('SpryAssets/SpryValidationRadio.js')}}" type="text/javascript"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="{{url('assets/js/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/Editeur.js')}}"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:60, minChars:2, validateOn:["change"], isRequired:true});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {maxChars:190, validateOn:["change"], isRequired:true});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {maxChars:190, validateOn:["change"], isRequired:false});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:6000, minChars:2, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>Créer une organisation</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li><a href="#creer">Créer</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li><a href="{{url('politique.php#organisations')}}">Organisations</a>
                    <span class="divider">/</span></li>
                <li class="active">Créer</li>
            </ul>

            <div class="well">
                {!! App\Services\HelperService::displayAlert() !!}
            </div>

            <div id="actualites" class="titre-vert anchor">
                <h1>Créer une organisation</h1>
            </div>

            <div class="well">

            <form method="POST" action="{{route('organisation.store')}}">

                @csrf

                <div id="sprytextfield1" class="control-group">
                    <label class="control-label" for="name">Nom de l'organisation <a href="#" rel="clickover" title="Nom de l'organisation" data-content="60 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xlarge" name="name" type="text" id="name" value="{{old('name')}}" maxlength="90">
                        <span class="textfieldMaxCharsMsg">60 caractères max.</span>
                    </div>
                </div>

                <div id="sprytextfield2" class="control-group">
                    <label class="control-label" for="flag">URL du drapeau <a href="#" rel="clickover" title="URL du drapeau" data-content="190 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xxlarge" name="flag" type="text" id="flag" value="{{old('flag')}}" maxlength="190">
                        <span class="textfieldMaxCharsMsg">190 caractères max.</span>
                    </div>
                </div>

                <div id="sprytextfield3" class="control-group">
                    <label class="control-label" for="logo">URL du logo <a href="#" rel="clickover" title="URL du logo" data-content="190 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xxlarge" name="logo" type="text" id="logo" value="{{old('logo')}}" maxlength="190">
                        <span class="textfieldMaxCharsMsg">190 caractères max.</span>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="allow_temperance">
                        <input class="checkbox" name="allow_temperance" type="checkbox" id="allow_temperance" value="1" {{ old('allow_temperance', 0) ? 'checked' : '' }} maxlength="190">
                        Calculer les données économiques <a href="#" rel="clickover" title="Calculer les données économiques" data-content="Cette case vous permet de définir que vous souhaitez que des statistiques économiques soient générés."><i class="icon-info-sign"></i></a></label>
                    <div class="controls"></div>
                </div>

                <div id="sprytextarea1" class="control-group">
                    <label class="control-label" for="text">Présentation</label>
                    <div class="controls">
                      <textarea name="text" id="text" class="wysiwyg" rows="15">{{old('text')}}</textarea>
                      <br>
                      <span class="textareaMaxCharsMsg">6000 caractères maximum.</span>
                      <span class="textareaMinCharsMsg">2 caractères minimum.</span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="pays_id">Choisir le propriétaire de l'organisation :</label>
                    <select name="pays_id" id="pays_id">
                        @foreach($pays as $thisPays):
                            <option value="{{$thisPays->ch_pay_id}}">{{$thisPays->ch_pay_nom}}</option>
                        @endforeach
                    </select>
                </div>

                <input type="submit" class="btn btn-primary" value="Envoyer">

            </form>

            </div>

        </div>

    </div>
    </div>

@endsection