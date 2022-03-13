
<form action="{{ route('roleplay.create-organizer', $roleplay) }}" method="POST">
    @csrf

    <x-blocks.roleplayable-selector />

    <button type="submit" class="btn btn-primary">
        Envoyer
    </button>

</form>

<a href="#" class="btn btn-primary component-trigger"
    {!! $getTargetHtmlAttributes(route('roleplay.manage-organizers', $roleplay), 'organizers') !!}>
    <i class="icon-white icon-backward"></i> Retour
</a>
