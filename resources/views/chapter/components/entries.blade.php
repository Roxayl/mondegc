
<div class="row">
    <div class="span10 offset1">

        @can('createEntries', $chapter)
            <div id="chapter-entry-add-container-{{ $chapter->identifier }}" class="mb-4 chapter-entry-add-container">
                <x-chapter-entry.create-button :chapter="$chapter" />
            </div>
        @endcan

        @php $lastDate = null; @endphp

        @foreach($chapter->entries as $entry)

            @if($lastDate === null || $lastDate !== $entry->created_at->format('d/m/Y'))
                <div class="mt-3 mb-0 chapter-entry-date-header">
                    <small>{{ $entry->created_at->translatedFormat('j F Y') }}</small>
                </div>
                @php $lastDate = $entry->created_at->format('d/m/Y') @endphp
            @endif

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

                <small class="mx-0 mt-1 mb-2 p-0" style="display: block;">
                    @if($entry->roleplayable !== null)
                        @if(! empty($entry->roleplayable->getFlag()))
                            <img src="{{ $entry->roleplayable->getFlag() }}" alt="" class="img-menu-drapeau" />
                        @endif
                        <a href="{{ $entry->roleplayable->accessorUrl() }}">{{ $entry->roleplayable->getName() }}</a>
                    @else
                        <i>Auteur inconnu.</i>
                    @endif
                </small>

                <h4 class="mt-0">{{ $entry->title }}</h4>

                {!! \Roxayl\MondeGC\Services\HelperService::purifyHtml($entry->content) !!}

                {!! $entry->mediaViewComponent()?->render() !!}

                <small class="mb-1 mt-3 mx-0 p-0" style="display: block;">
                    {{ $entry->created_at->format('d/m/Y à H:i') }}
                </small>
            </div>

        @endforeach

    </div>
</div>
