
<form action="{{ route('roleplay.create-organizer', $roleplay) }}" method="POST">
    @csrf

    <select name="type" id="organizer-type">
        <option value="organisation">Organisation
        <option value="pays">Pays</option>
        <option value="ville">Ville</option>
    </select>

    <input type="text" name="id" id="organizer-id-autocomplete" />

    <input type="hidden" name="id" id="organizer-id" />

    <button type="submit" class="btn btn-outline-primary">Envoyer</button>

</form>

<a href="#" class="btn btn-primary component-trigger"
    {!! $getTargetHtmlAttributes(route('roleplay.manage-organizers', $roleplay), 'organizers') !!}>
    <i class="icon-white icon-backward"></i> Retour
</a>

<script>
    (function($, document, window) {
        let endpointUrl = "{{ route('roleplay.roleplayables') }}";

        let $typeField = $('#organizer-type');
        let $autocomplete = $('#organizer-id-autocomplete');
        let $hiddenIdField = $('#organizer-id');
        $autocomplete.autocomplete({
            source: endpointUrl,
            select: function(event, ui) {
                console.log(ui.item);
                $autocomplete.val(ui.item.label);
                $hiddenIdField.val(ui.item.value);
                return false;
            }
        });
    })(jQuery, document, window);
</script>
