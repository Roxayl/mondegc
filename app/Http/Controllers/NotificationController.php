<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request) {

        $unread = auth()->user()->unreadNotifications;
        $count = max(10, $unread->count());
        $notifications = auth()->user()->notifications->take($count);

        return view('notification.show', compact(['notifications', 'unread']));

    }

    public function markAsRead(Request $request) {

        auth()->user()->unreadNotifications->markAsRead();
        return response();

    }
}
