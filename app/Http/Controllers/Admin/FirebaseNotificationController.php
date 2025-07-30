<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseNotificationService;
use App\Http\Traits\Api_Trait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class FirebaseNotificationController extends Controller
{
    use Api_Trait;

    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send notification to authenticated user's devices
     */
    public function sendToAuthenticatedUser(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'body' => 'required|string',
                'data' => 'nullable|array'
            ]);

            $user = userApi()->user();
            if (!$user) {
                return $this->returnError([helperTrans('api.Unauthorized')], 401);
            }

            $success = $this->firebaseService->sendToUser(
                $user,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            if (!$success) {
                return $this->returnError([helperTrans('api.Failed to send notification')]);
            }

            return $this->returnSuccessMessage([helperTrans('api.Notification sent successfully')]);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

    /**
     * Send notification to a specific user
     */
    public function sendPushNotification(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string',
                'body' => 'required|string',
                'data' => 'nullable|array'
            ]);

            $user = User::findOrFail($request->user_id);

            $success = $this->firebaseService->sendToUser(
                $user,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            if (!$success) {
                return $this->returnError([helperTrans('api.Failed to send notification')]);
            }

            return $this->returnSuccessMessage([helperTrans('api.Notification sent successfully')]);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

    /**
     * Subscribe user to a topic
     */
    public function subscribeToTopic(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'topic' => 'required|string'
            ]);

            $user = User::findOrFail($request->user_id);

            $success = $this->firebaseService->subscribeToTopic(
                $user,
                $request->topic
            );

            if (!$success) {
                return $this->returnError([helperTrans('api.Failed to subscribe to topic')]);
            }

            return $this->returnSuccessMessage([helperTrans('api.Subscribed to topic successfully')]);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

    public function sendToTopic(Request $request)
    {
        try {
            $request->validate([
                'topic' => 'required|string',
                'title' => 'required|string',
                'body' => 'required|string',
                'data' => 'nullable|array'
            ]);

            $success = $this->firebaseService->sendToTopic(
                $request->topic,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            if (!$success) {
                return $this->returnError('Failed to send notification to topic');
            }

            return $this->returnSuccessMessage('Notification sent to topic successfully');
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

    /**
     * Test notification with a specific token
     */
    public function testNotification(Request $request)
    {
        // try {
            $request->validate([
                'token' => 'required|string',
                'title' => 'required|string',
                'body' => 'required|string',
                'data' => 'nullable|array'
            ]);

            $success = $this->firebaseService->sendToMultipleTokens(
                [$request->token],
                $request->title,
                $request->body,
                $request->data ?? []
            );
            dd($success);
            if (!$success) {
                return $this->returnError('Failed to send test notification');
            }

            return $this->returnSuccessMessage('Test notification sent successfully');
        // } catch (\Exception $e) {
        //     return $this->returnExceptionError($e);
        // }
    }
}
