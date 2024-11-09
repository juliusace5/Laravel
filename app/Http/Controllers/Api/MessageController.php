<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:500'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'content' => $validated['content']
        ]);

        return response()->json($message, 201);
    }

    public function fetchConversation($receiverId)
    {
        $userId = Auth::id();

        $messages = Message::where(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $userId)->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $receiverId)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }
    public function markAsRead($senderId)
    {
        Message::where('sender_id', $senderId)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['message' => 'Messages marked as read']);
    }

    public function fetchAllConversations()
    {
        $userId = auth()->id();
    
        // Fetch messages where the user is either sender or receiver
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->get()
            ->unique(function ($message) {
                // Ensure uniqueness by pairing sender and receiver
                return $message->sender_id < $message->receiver_id
                    ? $message->sender_id . '-' . $message->receiver_id
                    : $message->receiver_id . '-' . $message->sender_id;
            })
            ->map(function ($message) use ($userId) {
                $otherUser = $message->sender_id === $userId ? $message->receiver : $message->sender;
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'content' => $message->content,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at,
                    'updated_at' => $message->updated_at,
                    'sender_name' => $message->sender->name,
                    'sender_avatar' => $message->sender->avatar,
                    'receiver_name' => $message->receiver->name,
                    'receiver_avatar' => $message->receiver->avatar,
                ];
            });
    
        return response()->json($messages);
    }
    




}