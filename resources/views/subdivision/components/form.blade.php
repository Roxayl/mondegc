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
        var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {
            maxChars:60, minChars:2, validateOn:["change"], isRequired:true
        });
    </script>
@endsection

@csrf

<div class="control-group">
    <label class="control-label" for="subdivisionType">
        Type
    </label>
    <select name="subdivisionType" @if($isEdit) disabled @endif>
        @foreach($pays->subdivisionTypes as $subdivisionType)
            <option value="{{ $subdivisionType->getKey() }}"
                @if($preselectedType == $subdivisionType->getKey()) selected @endif
                >{{ $subdivisionType->type_name }}</option>
        @endforeach
    </select>
</div>

<div id="sprytextfield1" class="control-group">
    <label class="control-label" for="name">
        Nom<span class="label-required">*</span>
        <a href="#" rel="clickover" title="Nom" data-content="60 caractères maximum.">
            <i class="icon-info-sign"></i>
        </a>
    </label>
    <div class="controls">
        <input class="input-xlarge" name="name" type="text" id="name" value="{{ old('name', $subdivision->name) }}" maxlength="120">
        <span class="textfieldMaxCharsMsg">90 caractères max.</span>
        <span class="textfieldMinCharsMsg">2 caractères minimum.</span>
        <span class="textfieldRequiredMsg">Ce champ est obligatoire.</span>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="summary">Résumé<span class="label-required">*</span></label>
    <textarea id="summary" class="form-control span9" rows="6"
           name="summary">{{ old('summary', $subdivision->summary) }}</textarea>
</div>

<div class="control-group">
    <label class="control-label" for="content">Présentation</label>
    <textarea id="content" class="wysiwyg form-control span9" rows="15"
              name="content">{{ old('content', $subdivision->content) }}</textarea>
</div>

<button type="submit" class="btn btn-primary">
    Envoyer
</button>
