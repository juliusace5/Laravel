<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get all notifications for the authenticated user
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with('actor', 'post')
            ->latest()
            ->get();

        \Log::info('Notifications fetched for user ID ' . Auth::id(), ['notifications' => $notifications]);

        return response()->json($notifications);
    }

    // Mark a notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)->where('user_id', Auth::id())->first();
        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Notification marked as read']);
        }
        return response()->json(['message' => 'Notification not found'], 404);
    }

    // Optional: Mark all notifications as read
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())->update(['read' => true]);
        return response()->json(['message' => 'All notifications marked as read']);
    }
}
