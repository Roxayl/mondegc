
@section('styles')
    <link href="{{url('SpryAssets/SpryValidationSelect.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('SpryAssets/SpryValidationTextField.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('SpryAssets/SpryValidationTextarea.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('SpryAssets/SpryValidationRadio.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
@endsection

@section('scripts')
    <!-- SPRY ASSETS -->
    <script src="{{url('SpryAssets/SpryValidationSelect.js')}}" type="text/javascript"></script>
    <script src="{{url('SpryAssets/SpryValidationTextField.js')}}" type="text/javascript"></script>
    <script src="{{url('SpryAssets/SpryValidationTextarea.js')}}" type="text/javascript"></script>
    <script src="{{url('SpryAssets/SpryValidationRadio.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <script>
        var sprytextfield_nom_infra = new Spry.Widget.ValidationTextField("sprytextfield_nom_infra", "none", {maxChars:250, minChars:2, validateOn:["change"], isRequired:true});
        var sprytextfield = new Spry.Widget.ValidationTextField("sprytextfield", "url", {maxChars:250, validateOn:["change"], isRequired:true});
        var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {maxChars:250, validateOn:["change"], isRequired:false});
        var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {maxChars:250, validateOn:["change"], isRequired:false});
        var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "url", {maxChars:250, validateOn:["change"], isRequired:false});
        var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {maxChars:250, validateOn:["change"], isRequired:false});
        var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "url", {maxChars:250, validateOn:["change"], isRequired:true});
        var sprytextfield_lien_wiki = new Spry.Widget.ValidationTextField("sprytextfield_lien_wiki", "url", {maxChars:250, validateOn:["change"], isRequired:false});
        var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["change"], maxChars:400, isRequired:false, useCharacterMasking:false});

        $(document).ready(function () {
            $('select').selectize({
                sortField: 'text',
                dropdownParent: "body",
                onChange: function () {
                    $('#spryselect1').find('.control-label').html('<img class="pull-right" src="https://squirrel.roxayl.fr/media/icons/ajax-loader2.gif"> <i class="icon-time"></i> Chargement... ');
                    $('#form-infra-list').submit();
                }
            });
        });
    </script>
@endsection
