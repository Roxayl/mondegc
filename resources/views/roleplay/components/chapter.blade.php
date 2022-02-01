
@inject('helperService', '\App\Services\HelperService')

<div class="titre-bleu" id="{{ $chapter->identifier }}">
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
