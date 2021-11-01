
<form action="{{ route('roleplay.create-organizer', $roleplay) }}" method="POST">
    @csrf

    <select name="type" id="organizer-type">
        <option value="organisation">Organisation
        <option value="pays">Pays</option>
        <option value="ville">Ville</option>
    </select>

    <input type="text" name="id" id="organizer-id" />

    <button type="submit" class="btn btn-outline-primary">Envoyer</button>

</form>

<a href="#" class="btn btn-primary component-trigger"
    {!! $getTargetHtmlAttributes(route('roleplay.manage-organizers', $roleplay), 'organizers') !!}>
    <i class="icon-white icon-backward"></i> Retour
</a>
