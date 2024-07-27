<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function getChatHistory(Request $request)
    {
        $userId = $request->user()->id;
        $chats = Chat::where('user1_id', $userId)
                     ->orWhere('user2_id', $userId)
                     ->with(['user1', 'user2', 'messages' => function ($query) {
                         $query->orderBy('created_at', 'asc');
                     }])
                     ->get();
    
        return response()->json($chats);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $senderId = $request->user()->id;
        $receiverId = $validated['receiver_id'];

        $chat = Chat::firstOrCreate([
            'user1_id' => min($senderId, $receiverId),
            'user2_id' => max($senderId, $receiverId),
        ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $senderId,
            'message' => $validated['message'],
        ]);

        return response()->json($message);
    }
}
?>
