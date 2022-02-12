
<div class="form-group">
    <select name="type" id="organizer-type">
        <option value="organisation">Organisation
        <option value="pays">Pays</option>
        <option value="ville">Ville</option>
    </select>

    <input type="text" name="id" id="organizer-id-autocomplete" class="form-control-lg span6"
           placeholder="Recherche :" />

    <input type="hidden" name="id" id="organizer-id" />
</div>


<script>
    (function ($, document, window) {
        let endpointUrl = "{{ route('roleplay.roleplayables') }}";

        let $typeField = $('select#organizer-type');
        let $autocomplete = $('#organizer-id-autocomplete');
        let $hiddenIdField = $('input#organizer-id');

        let onTypeFieldChange = function() {
            $autocomplete.val('');
            $autocomplete.attr('placeholder', "Recherche : " + $typeField.val())
            $hiddenIdField.val('');
        };

        $typeField.on('change', onTypeFieldChange);
        onTypeFieldChange();

        $autocomplete.autocomplete({
            source: function (request, response) {
                $.getJSON(endpointUrl, {type: $typeField.val(), term: $autocomplete.val()},
                    response);
            },
            select: function (event, ui) {
                console.log(ui.item);
                $autocomplete.val(ui.item.label);
                $hiddenIdField.val(ui.item.value);
                return false;
            }
        });
    })(jQuery, document, window);
</script>
