<ul class="breadcrumb">
    <li><a href="{{ route('roleplay.index') }}">Événements</a>
        <span class="badge badge-info badge-beta">Bêta</span>
        <span class="divider">/</span></li>
    <li><a href="{{ route('roleplay.show', $chapter->roleplay) }}">
            {{ $chapter->roleplay->name }}</a>
        <span class="divider">/</span></li>
    <li><a href="{{ route('roleplay.show', $chapter->roleplay) }}#chapter-{{ $firstChapter->identifier }}">
            Chapitres</a>
        <span class="divider">/</span></li>
    <li><a href="{{ route('roleplay.show', $chapter->roleplay) }}#chapter-{{ $chapter->identifier }}">
            {{ $chapter->name }}</a>
        <span class="divider">/</span></li>
    <li class="active">Historique</li>
</ul>
