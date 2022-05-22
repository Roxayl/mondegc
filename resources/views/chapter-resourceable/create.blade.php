<div class="pull-right-cta">
    <a href="#" class="component-trigger btn btn-primary"
        {!! \App\View\Components\BaseComponent::getTargetHtmlAttributes(route('chapter-resourceable.show', $chapter),
            'chapter-resourceable-container-' . $chapter->identifier) !!}>
        Retour
    </a>
</div>

<form action="{{ route('chapter-resourceable.store', $chapter) }}"
      method="POST">
    <h4>Générer des ressources pour cette entité</h4>

    @include('chapter-resourceable.partials.form')
</form>
