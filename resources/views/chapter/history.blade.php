
@inject('helperService', 'Roxayl\MondeGC\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    Historique du chapitre {{ $chapter->order }} : {{ $chapter->name }}
@endsection

@section('content')

    @parent
    <div class="container">
    <div class="row-fluid">

        <div class="span12 corps-page">

            <div class="titre-bleu">
                <h1>
                    Historique du chapitre {{ $chapter->order }} : {{ $chapter->name }}
                    <span class="badge badge-info badge-beta">Bêta</span>
                </h1>
            </div>

            <ul class="breadcrumb">
                <li>Roleplay <span class="divider">/</span></li>
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

            <div class="clearfix"></div>

            <div class="well">
                {!! $helperService::displayAlert() !!}
            </div>

            <div class="pull-right" style="margin-top: -30px; margin-bottom: -20px;">
                {{ $versions->links() }}
            </div>
            <div class="clearfix"></div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Date de modification</th>
                        <th>Motif de la modification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($versions as $version)
                        <tr>
                            <td>{{ $version->responsible_user?->ch_use_login }}</td>
                            <td>{{ $version->created_at->format('Y/m/d à H:i:s') }}</td>
                            <td>{{ $version->reason }}</td>
                            <td>
                                <a href="{{ route('chapter.diff', [
                                        'version1' => $version,
                                        'version2' => $version->previous(),
                                    ]) }}"
                                    class="btn btn-primary diff-toggle-btn">
                                    <i class="icon-indent-left icon-white"></i> Comparer
                                </a>

                                @if($canRevert && ! $version->isLast())
                                    <form method="POST" action="{{ route('version.revert', $version) }}"
                                          class="form-inline" style="display: inline;">
                                        @csrf
                                        <button class="btn btn-primary">
                                            <i class="icon-time icon-white"></i> Restaurer
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        <tr class="hidden diff-container">
                            <td colspan="4" style="margin-left: 30px;">
                                {{-- Diff chargé via AJAX --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $versions->links() }}

        </div>

    </div>
    </div>

@endsection

@section('scripts')

    @parent

    <script>
        (function($, document, window) {
            $(document).on('click', 'a.diff-toggle-btn', function(ev) {
                ev.preventDefault();

                let $btn = $(ev.currentTarget);
                let $nextTr = $btn.closest('tr').next();
                let url = $btn.attr('href');

                if(! $nextTr.hasClass('hidden')) {
                    $nextTr.addClass('hidden').find('td').html();
                    return;
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    beforeSend: function() {
                        $nextTr.removeClass('hidden');
                        $nextTr.find('td').html('<small>Chargement...</small>');
                    },
                    success: function(response) {
                        console.log($nextTr, $nextTr.find('td'));
                        $nextTr.find('td').html(response);
                    },
                });
            });
        })(jQuery, document, window);
    </script>

@endsection