
<div class="form-group" id="{{ $formId }}">
    <select name="type" id="organizer-type-{{ $formId }}">
        <option value="organisation"
            @if($roleplayable?->getType() === 'organisation') selected @endif
            >Organisation
        <option value="pays"
            @if($roleplayable?->getType() === 'pays') selected @endif
            >Pays</option>
        <option value="ville"
            @if($roleplayable?->getType() === 'ville') selected @endif>Ville</option>
    </select>

    <input type="text" name="id" id="organizer-id-autocomplete-{{ $formId }}" class="form-control-lg span6"
           placeholder="Recherche :"
           @if(! is_null($roleplayable)) value="{{ $roleplayable->getName() }}" @endif
    />

    <input type="hidden" name="id" id="organizer-id-{{ $formId }}"
           @if(! is_null($roleplayable)) value="{{ $roleplayable->getKey() }}" @endif/>
</div>

<script type="text/javascript">
    (function ($, document, window) {
        let endpointUrl = "{{ $endpointUrl }}";

        let $typeField = $('select#organizer-type-{{ $formId }}');
        let $autocomplete = $('#organizer-id-autocomplete-{{ $formId }}');
        let $hiddenIdField = $('input#organizer-id-{{ $formId }}');

        let onTypeFieldChange = function() {
            $autocomplete.val('');
            $autocomplete.attr('placeholder', "Recherche : " + $typeField.val());
            $hiddenIdField.val('');
        };

        $typeField.on('change', onTypeFieldChange);
        $autocomplete.attr('placeholder', "Recherche : " + $typeField.val());

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
