
<div class="row">
    <div class="span10 offset1">

        @foreach($chapter->entries as $entry)

            <div class="chapter-entry">
                {!! $entry->content !!}

                {!! $entry->mediaViewComponent()?->render() !!}
            </div>

        @endforeach

    </div>
</div>
