<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(): View
    {
        $unread = auth()->user()->unreadNotifications;

        $count = max(10, $unread->count());
        $notifications = auth()->user()->notifications->take($count);

        return view('notification.show', compact(['notifications', 'unread']));
    }

    public function markAsRead(Request $request): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
}
