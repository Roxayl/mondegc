
<blockquote>
    {!! \Roxayl\MondeGC\Services\HelperService::purifyHtml($entry->media_data['media']['text']) !!}
    <br>
    <small>
        {{ $entry->media_data['media']['author'] }} -
        <a href="{{ $entry->media_data['meta']['url'] }}">
            <img src="https://squirrel.roxayl.fr/media/icons/favicon-alt.ico" style="width: 16px;" alt="Squirrel">
            Squirrel
        </a>
    </small>
</blockquote>
