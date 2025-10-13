<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'notification' => $user->notifications()
        ]);
    }

    public function unread()
    {
        $user = auth()->user();

        return response()->json([
            'unread Notifications' => $user->unreadNotifications
        ]);
    }
    public function markAllsRead()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }
    public function markAsRead($notificationId)
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if($notification){
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        }
        return response()->json(['message' => 'Notification not found']);
    }






}
