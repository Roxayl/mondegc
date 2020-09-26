
@inject('notificationPresenter', 'App\Models\Presenters\NotificationPresenter')

@if($unread->count())
<div class="pull-right" style="margin-right: 10px; margin-top: -3px;">
    <form method="POST" action="{{ route('notification.mark-as-read') }}"
          class="notification-markasread">
        @csrf
        <input type="hidden" name="mark_unread" value="1">
        <button class="btn btn-primary" type="submit">Tout marquer comme lu</button>
    </form>
</div>
@endif

<h4 class="btn-margin-left">Notifications </h4>

@forelse($notifications as $notification)

    @php
    $notificationData = $notificationPresenter::getDisplayData($notification);
    if(!$notificationData) continue;
    @endphp

    <li class="@empty($notification->read_at) notification-unread @endempty">
        <a href="{{ $notificationData->link }}">
            <div class="row-fluid">
                <div class="pull-left">
                    <div class="notification-styler" style="{{ $notificationData->style }}"></div>
                </div>
                <div style="margin-left: 5px;">
                    <div class="pull-right">
                        <div class="notification-unread-pastille"></div>
                    </div>
                    <h4>{{ $notificationData->header }}</h4>
                    <p>
                        <small class="inline" style="margin: 0; padding: 0; color: #0a0a0a;">
                            {{ $notification->created_at->diffForHumans() }}</small>
                        {!! $notificationData->text !!}
                    </p>
                </div>
            </div>
        </a>
    </li>

@empty

    <div class="well">
        <p>Vous n'avez pas de notifications récentes. :)</p>
    </div>

@endforelse
