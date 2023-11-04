<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $allNotifications = auth()->user()->notifications;
        $unreadNotifications = auth()->user()->unreadNotifications;

        return view('notifications.index', compact('allNotifications', 'unreadNotifications'));
    }

    public function update(Request $request, DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return redirect()->route('notifications.index');
    }

    public function destroy()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index');
    }
}
