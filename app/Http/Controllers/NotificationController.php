<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return View
     */
    public function index(): View
    {
        if (! auth()->check()) {
            abort(403);
        }

        $unread = auth()->user()->unreadNotifications;

        $count = max(10, $unread->count());
        $notifications = auth()->user()->notifications->take($count);

        return view('notification.show', compact(['notifications', 'unread']));
    }

    /**
     * @return JsonResponse
     */
    public function markAsRead(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
}
