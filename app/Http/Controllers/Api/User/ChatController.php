<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Chat;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class ChatController extends Controller
{
    protected $chatRepository;
    protected $firebase;

    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
        $credentialsPath = base_path('public/firbase-credentails.json');
        $this->firebase = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->withDatabaseUri('https://egyptian-coach-default-rtdb.firebaseio.com')
            ->createDatabase(); // ✅ This returns a Database instance;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);
        $admin_id = Admin::where('is_active', 1)->first()->id;

        $chat = $this->chatRepository->create([
            'user_id' => $request->user_id,
            'admin_id' => $admin_id,
            'message' => $request->message,
            'is_admin' => false,
            'is_read' => false
        ]);

        // Send to Firebase
        $this->firebase->getReference('chats/' . $request->user_id)
            ->push([
                'message' => $request->message,
                'is_admin' => false,
                'timestamp' => now()->timestamp,
                'admin_id' => $admin_id
            ]);

        return response()->json(['message' => 'Message sent successfully', 'chat' => $chat]);
    }

    public function getUserMessages(Request $request)
    {
        $user = userApi()->user();
        $messages = $this->chatRepository->getUserMessages($user->id);
        return response()->json(['messages' => $messages]);
    }

    public function getAdminMessages(Request $request)
    {
        $user = userApi()->user();
        $messages = $this->chatRepository->getAdminMessages($user->id);
        return response()->json(['messages' => $messages]);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id'
        ]);

        $this->chatRepository->markAsRead($request->chat_id);
        return response()->json(['message' => 'Message marked as read']);
    }

    public function getUnreadCount(Request $request)
    {
        $user = userApi()->user();
        $unreadCount = $this->chatRepository->getUnreadCount($user->id);
        return response()->json(['unread_count' => $unreadCount]);
    }

    public function getLastMessage(Request $request)
    {
        $user = userApi()->user();
        $lastMessage = $this->chatRepository->getLastMessage($user->id);
        return response()->json(['last_message' => $lastMessage]);
    }
}
