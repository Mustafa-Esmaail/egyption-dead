<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\Topic;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class FirebaseNotificationService
{
    protected $messaging;
    protected $isInitialized = false;

    public function __construct()
    {
        $this->initializeFirebase();
    }

    protected function initializeFirebase()
    {
        try {
            $credentialsPath = base_path('public/firbase-credentails.json');

            if (!file_exists($credentialsPath)) {
                throw new \Exception('Firebase credentials file not found at: ' . $credentialsPath);
            }

            if (!is_readable($credentialsPath)) {
                throw new \Exception('Firebase credentials file is not readable at: ' . $credentialsPath);
            }

            $firebase = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri(config('firebase.database_url', 'https://your-project-id.firebaseio.com'));

            $this->messaging = $firebase->createMessaging();
            $this->isInitialized = true;

            Log::channel('firebase')->info('Firebase initialized successfully');
        } catch (\Exception $e) {
            $this->isInitialized = false;
            Log::channel('firebase')->error('Firebase initialization error: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function ensureInitialized()
    {
        if (!$this->isInitialized) {
            $this->initializeFirebase();
        }
    }

    protected function handleFirebaseException(\Exception $e, array $context = [])
    {
        if ($e instanceof ConnectException) {
            Log::channel('firebase')->error('Firebase connection error', array_merge($context, [
                'error' => $e->getMessage(),
                'error_type' => 'connection_error'
            ]));
            return false;
        }

        if ($e instanceof RequestException) {
            Log::channel('firebase')->error('Firebase request error', array_merge($context, [
                'error' => $e->getMessage(),
                'error_type' => 'request_error',
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]));
            return false;
        }

        Log::channel('firebase')->error('Firebase error', array_merge($context, [
            'error' => $e->getMessage(),
            'error_type' => 'general_error',
            'trace' => $e->getTraceAsString()
        ]));
        return false;
    }

    /**
     * Send notification to a user's devices
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToUser(User $user, string $title, string $body, array $data = [])
    {
        try {
            $this->ensureInitialized();

            // Get user's FCM tokens
            $tokens = $user->fcm_token ? json_decode($user->fcm_token, true) : [];

            if (empty($tokens)) {
                Log::channel('firebase')->warning('No FCM tokens found for user', [
                    'user_id' => $user->id
                ]);
                return false;
            }

            $successCount = 0;
            $failedTokens = [];

            // Send to each token
            foreach ($tokens as $token) {
                try {
                    // Create message with target first
                    $message = CloudMessage::withTarget('token', $token);

                    // Set notification
                    $message = $message->withNotification([
                        'title' => $title,
                        'body' => $body
                    ]);

                    // Add data if provided
                    if (!empty($data)) {
                        $message = $message->withData($data);
                    }

                    // Send the message
                    $response = $this->messaging->send($message);

                    Log::channel('firebase')->info('Notification sent successfully', [
                        'user_id' => $user->id,
                        'token' => $token,
                        'message_id' => $response['name'] ?? null
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $failedTokens[] = [
                        'token' => $token,
                        'error' => $e->getMessage()
                    ];

                    $this->handleFirebaseException($e, [
                        'user_id' => $user->id,
                        'token' => $token
                    ]);
                }
            }

            // Log summary
            Log::channel('firebase')->info('User notification summary', [
                'user_id' => $user->id,
                'total_tokens' => count($tokens),
                'successful_sends' => $successCount,
                'failed_sends' => count($failedTokens),
                'failed_tokens' => $failedTokens,
                'title' => $title,
                'body' => $body,
                'data' => $data
            ]);

            return $successCount > 0;
        } catch (\Exception $e) {
            return $this->handleFirebaseException($e, [
                'user_id' => $user->id,
                'title' => $title,
                'body' => $body
            ]);
        }
    }

    /**
     * Send notification to all users subscribed to a topic
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = [])
    {
        try {
            $this->ensureInitialized();

            // Create message with target first
            $message = CloudMessage::withTarget('topic', $topic);

            // Set notification
            $message = $message->withNotification([
                'title' => $title,
                'body' => $body
            ]);

            // Add data if provided
            if (!empty($data)) {
                $message = $message->withData($data);
            }

            // Send the message
            $response = $this->messaging->send($message);

            Log::channel('firebase')->info('Topic notification sent successfully', [
                'topic' => $topic,
                'message_id' => $response['name'] ?? null
            ]);

            return true;
        } catch (\Exception $e) {
            return $this->handleFirebaseException($e, [
                'topic' => $topic,
                'title' => $title,
                'body' => $body
            ]);
        }
    }

    /**
     * Subscribe a user to a topic
     *
     * @param User $user
     * @param string $topic
     * @return bool
     */
    public function subscribeToTopic(User $user, string $topic)
    {
        try {
            $this->ensureInitialized();

            $tokens = $user->fcm_token ? json_decode($user->fcm_token, true) : [];

            if (empty($tokens)) {
                Log::channel('firebase')->warning('No FCM tokens found for user', [
                    'user_id' => $user->id
                ]);
                return false;
            }

            $response = $this->messaging->subscribeToTopic($topic, $tokens);

            Log::channel('firebase')->info('User subscribed to topic successfully', [
                'user_id' => $user->id,
                'topic' => $topic,
                'tokens_count' => count($tokens)
            ]);

            return true;
        } catch (\Exception $e) {
            return $this->handleFirebaseException($e, [
                'user_id' => $user->id,
                'topic' => $topic
            ]);
        }
    }

    /**
     * Send notification to multiple tokens
     *
     * @param array $tokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToMultipleTokens(array $tokens, string $title, string $body, array $data = [])
    {
        try {
            $this->ensureInitialized();

            if (empty($tokens)) {
                Log::channel('firebase')->warning('No tokens provided for notification');
                return false;
            }

            $successCount = 0;
            $failedTokens = [];

            // Send to each token
            foreach ($tokens as $token) {
                try {
                    // Create message with target first
                    $message = CloudMessage::withTarget('token', $token);

                    // Set notification
                    $message = $message->withNotification([
                        'title' => $title,
                        'body' => $body
                    ]);

                    // Add data if provided
                    if (!empty($data)) {
                        $message = $message->withData($data);
                    }

                    // Send the message
                    $response = $this->messaging->send($message);

                    Log::channel('firebase')->info('Notification sent successfully', [
                        'token' => $token,
                        'message_id' => $response['name'] ?? null
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $failedTokens[] = [
                        'token' => $token,
                        'error' => $e->getMessage()
                    ];

                    $this->handleFirebaseException($e, [
                        'token' => $token
                    ]);
                }
            }

            Log::channel('firebase')->info('Multiple tokens notification sent', [
                'tokens_count' => count($tokens),
                'successful_sends' => $successCount,
                'failed_sends' => count($failedTokens),
                'failed_tokens' => $failedTokens,
                'title' => $title,
                'body' => $body,
                'data' => $data
            ]);

            return $successCount > 0;
        } catch (\Exception $e) {
            return $this->handleFirebaseException($e, [
                'tokens_count' => count($tokens),
                'title' => $title,
                'body' => $body
            ]);
        }
    }
}
