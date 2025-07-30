<?php

namespace App\Repositories;

use App\Models\Chat;
use Kreait\Firebase\Factory;
use Illuminate\Support\Collection;

class ChatRepository
{
    protected $model;
    protected $firebase;

    public function __construct(Chat $model)
    {
        $credentialsPath = base_path('public/firbase-credentails.json');
        $this->model = $model;
        $this->firebase = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->withDatabaseUri('https://egyptian-coach-default-rtdb.firebaseio.com')
            ->createDatabase(); // ✅ This returns a Database instance;
    }

    public function create(array $data): Chat
    {
        $chat = $this->model->create($data);

        // Send to Firebase
       $ref = $this->firebase->getReference('chats/' . $data['user_id'])
            ->push([
                'message' => $data['message'],
                'is_admin' => $data['is_admin'],
                'timestamp' => now()->timestamp,
                'admin_id' => $data['admin_id'] ?? null
            ]);

        return $chat;
    }

    public function getUserMessages(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getAdminMessages(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function markAsRead(int $chatId): bool
    {
        $chat = $this->model->find($chatId);
        return $chat->update(['is_read' => true]);
    }

    public function find(int $id): ?Chat
    {
        return $this->model->find($id);
    }

    public function markMessagesAsRead($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->where('is_admin', false)
            ->update(['is_read' => true]);
    }

    public function getUnreadCount($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->where('is_admin', false)
            ->count();
    }

    public function getLastMessage($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
