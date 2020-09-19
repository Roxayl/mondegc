
@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" @endsection

@section('styles')
<style>
    .jumbotron {
        @if(!empty($organisation->flag))
            background-image: url('{{$organisation->flag}}');
        @else
            background: linear-gradient(to right, #f80000 0%,#d000dd 72%);
            background-size: 200%;
        @endif
    }
</style>
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
