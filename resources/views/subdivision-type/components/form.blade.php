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
    <script type="text/javascript">
        var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {
            maxChars:60, minChars:2, validateOn:["change"], isRequired:true
        });
    </script>
@endsection

@csrf

<div id="sprytextfield1" class="control-group">
    <label class="control-label" for="name">
        Nom de l'entité<span class="label-required">*</span>
        <a href="#" rel="clickover" title="Nom du type de subdivision" data-content="60 caractères maximum. Par exemple, région, préfecture, état, province, ...">
            <i class="icon-info-sign"></i>
        </a>
    </label>
    <div class="controls">
        <input class="input-xlarge" name="type_name" type="text" id="type_name" value="{{ old('type_name', $subdivisionType->type_name) }}" maxlength="90" placeholder="ex: Région, Préfecture, État, Province, ...">
        <span class="textfieldMaxCharsMsg">60 caractères max.</span>
        <span class="textfieldMinCharsMsg">2 caractères minimum.</span>
        <span class="textfieldRequiredMsg">Ce champ est obligatoire.</span>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    Envoyer
</button>
