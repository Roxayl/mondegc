<div class="pull-right-cta">
    <a href="#" class="component-trigger btn btn-primary"
        {!! \App\View\Components\BaseComponent::getTargetHtmlAttributes(route('chapter-resourceable.show', $chapter),
            'chapter-resourceable-container-' . $chapter->identifier) !!}>
        Retour
    </a>
</div>

<form action="{{ route('chapter-resourceable.store', $chapter) }}"
      method="POST">
    @csrf

    <h4>Générer des ressources pour cette entité</h4>
    <x-blocks.roleplayable-selector />

    <h4>Attribution des ressources</h4>

    <div class="form-control">
        <label for="chapter_resourceable_description">Pourquoi cette entité génère des ressources ?</label>
        <input type="text" id="chapter_resourceable_description" style="width: 100%"
               placeholder="Expliquez les raisons pour lesquelles cette entité bénéficie de ressources dans le cadre de ce roleplay."/>
    </div>

    <div class="well">
        <x-blocks.resource-selector />
    </div>

    <div class="form-control">
        <button type="submit" class="btn btn-primary">
            Valider
        </button>
    </div>
</form>
