
<div class="row">
    <div class="span10 offset1">

        <div id="chapter-entry-add-container-{{ $chapter->identifier }}" class="mb-4 chapter-entry-add-container">
            <x-chapter-entry.create-button :chapter="$chapter" />
        </div>

        @foreach($chapter->entries as $entry)

            <div class="chapter-entry">
                {!! $entry->content !!}

                {!! $entry->mediaViewComponent()?->render() !!}
            </div>

        @endforeach

    </div>
</div>
