
<div class="row">
    <div class="span10 offset1">

        @can('createEntries', $chapter)
            <div id="chapter-entry-add-container-{{ $chapter->identifier }}" class="mb-4 chapter-entry-add-container">
                <x-chapter-entry.create-button :chapter="$chapter" />
            </div>
        @endcan

        @foreach($chapter->entries as $entry)

            <div class="chapter-entry">
                @can('manage', $entry)
                    <div class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle p-2" data-toggle="dropdown">
                            &#8226; &#8226; &#8226;
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a tabindex="-1" href="{{ route('chapter-entry.delete', $entry) }}"
                                   data-toggle="modal" data-target="#modal-container-small">
                                    <i class="icon-trash"></i> Supprimer</a>
                            </li>
                        </ul>
                    </div>
                @endcan

                <small class="mx-0 mt-1 mb-3 p-0" style="display: block;">
                    @if($entry->roleplayable !== null)
                        <a href="{{ $entry->roleplayable->accessorUrl() }}">{{ $entry->roleplayable->getName() }}</a>
                    @else
                        <i>Auteur inconnu.</i>
                    @endif
                </small>

                {!! $entry->content !!}

                {!! $entry->mediaViewComponent()?->render() !!}
            </div>

        @endforeach

    </div>
</div>
