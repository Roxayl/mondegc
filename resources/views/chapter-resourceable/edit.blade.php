<div class="pull-right-cta">
    <a href="#" class="component-trigger btn btn-primary"
        {!! \Roxayl\MondeGC\View\Components\BaseComponent::getTargetHtmlAttributes(route('chapter-resourceable.manage', $chapter),
            'chapter-resourceable-container-' . $chapter->identifier) !!}>
        Retour
    </a>
</div>

<form action="{{ route('chapter-resourceable.update', $chapterResourceable) }}"
      method="POST">
    @method('PUT')

    <h4>Modifier les ressources générées par cette entité</h4>

    @include('chapter-resourceable.partials.form')
</form>
