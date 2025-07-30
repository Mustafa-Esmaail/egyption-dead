<?php

namespace App\Http\Controllers\Api\User\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Api_Trait;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AuthUserResource;


class SocialLoginController extends Controller
{
    use Api_Trait;


    public function socialLogin(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'email' => 'required|email',
                'platform' => 'required|in:google,facebook',
                'fcm_token' => 'required',
            ],
            []
        );

        if ($validator->fails()) {
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
        }

        try {

            $user = User::where('email', $request->email)->first();

            $newUser = false;
            if (!$user) {


                // Add new token if it doesn't exist
                $tokens[] = $request->fcm_token;

                // Create new user with a random password
                $password = '123456789';
                $user = User::create([
                    'first_name' => $request->first_name,
                    'social_provider' => $request->platform,
                    'email' => $request->email,
                    'fcm_token' => json_encode($tokens)

                ]);
                $newUser = true;

                increment_User_points('register', dynamicSetting('signUp_points'));
            } else {
                $tokens = $user->fcm_token ? json_decode($user->fcm_token, true) : [];

                // Update existing user's social provider info
                $user->update([
                    'social_provider' => $request->platform,
                    'fcm_token' => json_encode($tokens)
                ]);
            }
            // Get current tokens or initialize empty array





            if ($user) {

                $token = Auth::guard('user')->login($user);

                $user->update(['social_provider' => $request->platform]);

                $user = userApi()->user();

                $user->token = $token;
                $user->new_user = $newUser;


                return $this->returnData(AuthUserResource::make($user), [helperTrans('api.login successfully')]);
            }

            return $this->returnError([helperTrans('api.failed to login')], 403);
        } catch (Exception $e) {

            return $this->return_exception_error($e);
        }
    }
}
