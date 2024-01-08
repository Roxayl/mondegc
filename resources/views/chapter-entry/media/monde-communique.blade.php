
@if($communique === null)
    <div class="alert alert-info">
        Ce communiqué n'existe plus.
    </div>

@else
    <blockquote>
        {!! \Roxayl\MondeGC\Services\HelperService::purifyHtml($communique->ch_com_contenu) !!}
        <br>
        <small>
            {{ $communique->publisher()?->getName()  }} -
            <a href="{{ $entry->media_data['meta']['url'] }}">
                <img src="{{ url('assets/ico/favicon.ico') }}" style="width: 16px;" alt="Le Monde GC">
                Monde GC - Communiqué
            </a>
        </small>
    </blockquote>

@endif
