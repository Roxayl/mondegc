<div class="well">
    @empty($diffs)
        <p><i class="icon-info-sign"></i> Aucun diff disponible.</p>
    @endempty
    @foreach($diffs as $field => $diff)
        <small style="display: block;">{{ $field }}</small>
        {!! $diff !!}
        <hr>
    @endforeach
</div>
