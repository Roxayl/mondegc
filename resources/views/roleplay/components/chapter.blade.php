
@inject('helperService', '\App\Services\HelperService')

<div class="titre-bleu" id="{{ $chapter->identifier }}">
    <h1>{{ $chapter->title }}</h1>
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
