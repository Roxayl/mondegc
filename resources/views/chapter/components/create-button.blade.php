
<div class="well" style="margin-bottom: 20px;">
    <a href="#" class="btn btn-primary btn-cta component-trigger"
       {!! \App\View\Components\BaseComponent::getTargetHtmlAttributes(
            route('chapter.create', ['roleplay' => $roleplay]), 'chapter-create') !!}>
        <i class="icon-white icon-pencil"></i> Créer un chapitre
    </a>
</div>
