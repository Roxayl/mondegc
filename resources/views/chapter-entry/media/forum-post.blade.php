
<blockquote>
    {!! \Roxayl\MondeGC\Services\HelperService::purifyHtml($entry->media_data['media']['text']) !!}
    <br>
    <small>
        {{ $entry->media_data['media']['author'] }} -
        <a href="{{ $entry->media_data['meta']['url'] }}">
            <img src="https://2img.net/h/www.generation-city.com/forum/new/favicon.png" alt="Forum GC"/>
            Forum GC
        </a>
    </small>
</blockquote>
