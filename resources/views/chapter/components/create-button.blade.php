
<div class="well" style="margin-bottom: 20px;">
    <a href="#" class="btn btn-primary btn-cta component-trigger"
       {!! \Roxayl\MondeGC\View\Components\BaseComponent::getTargetHtmlAttributes(
            route('chapter.create', ['roleplay' => $roleplay]), 'chapter-create') !!}>
        <i class="icon-white icon-pencil"></i> Créer un chapitre
    </a>

    <a href="{{ route('roleplay.confirm-close', $roleplay) }}" class="btn btn-warning"
       data-toggle="modal" data-target="#modal-container-small">
        Clôturer ce roleplay
    </a>
</div>
