
@inject('helperService', '\App\Services\HelperService')

<div id="chapter-container-{{ $chapter->identifier }}">

    <div class="cta-title pull-right-cta" style="margin-top: 7px;">
        <a href="{{ route('chapter.history', $chapter) }}" class="btn btn-primary btn-cta">
            <i class="icon-time icon-white"></i> Historique
        </a>
        <a href="#" id="chapter-{{ $chapter->identifer }}-plus" class="btn btn-primary btn-cta component-trigger"
           {!! $getTargetHtmlAttributes(route('chapter.edit', $chapter),
                                        'chapter-container-' . $chapter->identifier) !!}>
            <i class="icon-white icon-pencil"></i> Modifier</a>
        <div class="dropdown" style="display: inline;">
            <a href="#" class="btn btn-primary btn-cta dropdown-toggle" data-toggle="dropdown">
                <i class="icon-plus icon-white"></i> Plus...
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="chapter-{{ $chapter->identifer }}-plus">
                <li><a tabindex="-1" href="{{ route('chapter.delete', $chapter) }}"
                       data-toggle="modal" data-target="#modal-container-small">
                        <i class="icon-trash"></i> Supprimer le chapitre</a></li>
            </ul>
        </div>
    </div>

    <div class="titre-bleu" id="chapter-{{ $chapter->identifier }}">
        <h1>
            {{ $chapter->title }}
            @if($chapter->isCurrent())
                <span class="badge badge-info inline">En cours</span>
            @endif
        </h1>
    </div>

    <div class="well">
        <strong>{{ $helperService::purifyHtml($chapter->summary) }}</strong>
    </div>

    <div class="well">
        {!! $helperService::purifyHtml($chapter->content) !!}
    </div>

    <div class="well">
        <x-roleplay.chapter-resources :chapter="$chapter" />
    </div>

</div>
