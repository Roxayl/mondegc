
<div class="pull-right-cta">
    <a href="#" class="component-trigger btn btn-primary"
            {!! $getTargetHtmlAttributes(route('chapter-resourceable.create', $chapter),
                'chapter-resourceable-container-' . $chapter->identifier) !!}>
        Générer des ressources
    </a>
</div>

@if($chapter->resourceables->isEmpty())

    <small>
        <i class="icon-info-sign"></i> Pas de ressources générées au cours de ce chapitre.
    </small>

@else

    <h4>Ressources générées au cours de ce chapitre...</h4>

    @foreach($chapter->resourceables as $chapterResourceable)

        <div>
            <img src="{{ $chapterResourceable->resourceable->getFlag() }}" class="img-menu-drapeau"
                 alt="Drapeau de {{ $chapterResourceable->resourceable->getName() }}" />

            <a href="{{ $chapterResourceable->resourceable->accessorUrl() }}">
                {{ $chapterResourceable->resourceable->getName() }}
            </a>

            {!! \App\Services\HelperService::renderLegacyElement('temperance/resources_small', [
                'resources' => $chapterResourceable->resources()
            ]) !!}
        </div>

    @endforeach

@endempty
