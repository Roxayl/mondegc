<div class="cta-title pull-right-cta">
    <a href="#" class="btn btn-primary btn-cta component-trigger"
        {!! $getTargetHtmlAttributes(route('chapter.create-button', $roleplay), 'chapter-create') !!}>
        <i class="icon-white icon-eye-close"></i> Annuler</a>
</div>

<div class="titre-bleu" id="{{ $chapter->identifier }}">
    <h1>Créer un chapitre</h1>
</div>

@include('chapter.components.form')
