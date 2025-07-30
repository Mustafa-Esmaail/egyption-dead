<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function socialLogin(Request $request)
    {
        try {
            $request->validate([
                'provider' => 'required|in:facebook,google',
                'access_token' => 'required|string',
            ]);

            $provider = $request->provider;
            $accessToken = $request->access_token;

            // Get user data from provider
            $socialUser = Socialite::driver($provider)
            ->stateless()
            ->userFromToken($accessToken);
            // Check if user exists
            $user = User::where('email', $socialUser->getEmail())->first();
            $newUser = false;
            if (!$user) {
                // Create new user with a random password
                $password = '123456789';
                $user = User::create([
                    'first_name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail()?? fake()->email(),
                    'password' => Hash::make($password),
                    'social_provider' => $provider,
                    'social_provider_id' => $socialUser->getId(),
                ]);
                $newUser = true;
            } else {
                // Update existing user's social provider info
                $user->update([
                    'social_provider' => $provider,
                    'social_provider_id' => $socialUser->getId(),
                ]);
            }


            // Generate token using the same method as AuthController
            $token = userApi()->attempt(['email' => $user->email, 'password' => $user->password], 1);

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Authentication failed',
                    'error' => 'Invalid credentials'
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'new_user' => $newUser
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
