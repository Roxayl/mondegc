
<div class="pull-right-cta">
    @can('createResourceables', $chapter)
        <a href="#" class="component-trigger btn btn-primary"
            {!! \Roxayl\MondeGC\View\Components\BaseComponent::getTargetHtmlAttributes(route('chapter-resourceable.show', $chapter),
                'chapter-resourceable-container-' . $chapter->identifier) !!}>
            Retour
        </a>
        <a href="#" class="component-trigger btn btn-primary"
            {!! \Roxayl\MondeGC\View\Components\BaseComponent::getTargetHtmlAttributes(route('chapter-resourceable.create', $chapter),
                'chapter-resourceable-container-' . $chapter->identifier) !!}>
            Générer des ressources
        </a>
    @endcan
</div>

<h4>Gérer les ressources générées au cours de ce chapitre...</h4>

@forelse($chapter->resourceables as $chapterResourceable)
    <div class="pull-right">
        <a href="#" class="component-trigger"
            {!! \Roxayl\MondeGC\View\Components\BaseComponent::getTargetHtmlAttributes(route('chapter-resourceable.edit', $chapterResourceable),
                'chapter-resourceable-container-' . $chapter->identifier) !!}>
            <i class="icon-edit"></i>
        </a>
        <a href="{{ route('chapter-resourceable.delete', $chapterResourceable) }}"
           data-toggle="modal" data-target="#modal-container-small">
            <i class="icon-trash"></i>
        </a>
    </div>

    <div>
        <img src="{{ $chapterResourceable->resourceable->getFlag() }}" class="img-menu-drapeau"
             alt="Drapeau de {{ $chapterResourceable->resourceable->getName() }}" />

        <a href="{{ $chapterResourceable->resourceable->accessorUrl() }}">
            {{ $chapterResourceable->resourceable->getName() }}
        </a>

        {!! \Roxayl\MondeGC\Services\HelperService::renderLegacyElement('temperance/resources_small', [
            'resources' => $chapterResourceable->resources()
        ]) !!}
        @if(! empty($chapterResourceable->description))
            <br><em style="margin-left: 45px;">{{ $chapterResourceable->description }}</em>
        @endif
    </div>

    <div class="clearfix"></div>

@empty
    <small>
        <i class="icon-info-sign"></i> Pas de ressources générées au cours de ce chapitre.
    </small>

@endforelse
