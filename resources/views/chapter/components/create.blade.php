<div class="cta-title pull-right-cta" style="margin-top: 7px;">
    <a href="#" class="btn btn-primary btn-cta component-trigger"
        {!! $getTargetHtmlAttributes(route('chapter.create-button', $roleplay), 'chapter-create') !!}>
        <i class="icon-white icon-eye-close"></i> Annuler</a>
</div>

<div class="titre-bleu" id="{{ $chapter->identifier }}">
    <h1>Créer un chapitre</h1>
</div>

<div class="clearfix"></div>

<div class="well">
    <form method="POST" action="{{ route('chapter.store', ['roleplay' => $roleplay]) }}">
        @include('chapter.components.form')
    </form>
</div>
