<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use App\Http\Traits\Api_Trait;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\Rule;
use App\Http\Traits\Upload_Files;
// use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //
    use Api_Trait,Upload_Files;
    //
    public function register(Request $request){

        try{

            $rules=[
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'phone' => 'required|regex:/^0[0-9]{10}$/|unique:users,phone',
                'type' => 'required|in:male,female',
                'email' => 'required|email',
                'password' => 'required',
                // 'fcm_token' => 'required',
            ];

            // dd($request->email);
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            DB::beginTransaction();

            $user = User::where('email',$request->email)
                // ->orWhere('phone',$request->phone)
                ->first();
            // dd($user);
            if($user){

                $user->update(['is_active'=>1]);

                return $this->returnError([helperTrans('api.this email already exists, go for login')]);
            }
            // dd($user);
            $data = $request->only('first_name', 'last_name','phone','type', 'email','password');

            $data['password'] = bcrypt($data['password']);
            // $data['fcm_token'] = $request->fcm_token;

            // dd('wwww');
           $user = user::create($data);

            // $token = userApi()->attempt(['email' => $data['email'], 'password' => $request->password], 1);
            // dd($data['email'],$request->password);
            $token =  userApi()->attempt(['email' => $data['email'], 'password' => $request->password], 1);

            // $token = $user->createToken('user')->plainTextToken;
            // dd($token);
            if ($token) {

                increment_User_points('register',dynamicSetting('signUp_points'));

                $user = userApi()->user();

                $user->token = $token;

                DB::commit();
                // dd($user);
                return $this->returnData(AuthUserResource::make($user),[helperTrans('api.login successfully')]);
            }else {

                return $this->returnErrorValidation(collect(['Error In login please try login again']), 403);
            }

        }catch(Exception $e){

            DB::rollBack();
            return  $this->errorResponse($e->getMessage());
         }

    }

    // ******************************************************************
    // ******************************************************************
    public function login(Request $request){

        try{

            // dd(11111);
            $login_value = $request->email;

            $rules = [
                'password' => 'required',
            ];

            if (filter_var($login_value, FILTER_VALIDATE_EMAIL)) {
                $rules['email'] = 'required|email|exists:users,email';
            } elseif (preg_match('/^0[0-9]{10}$/', $login_value)) {
                $rules['phone'] = 'required|regex:/^0[0-9]{10}$/|exists:users,phone';
            } else {
                return $this->returnErrorValidation(collect([helperTrans('api.Invalid email or phone number must be 11 digits starting with 0')]), 403);
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            if (filter_var($login_value, FILTER_VALIDATE_EMAIL)) {
                $credentials = $request->only('email', 'password');
            } else {
                $credentials = [
                    'phone' => $login_value,
                    'password' => $request->password,
                ];
            }

            $token = userApi()->attempt($credentials, 1);

            if ($token) {

                $user = userApi()->user();

                $user->update([
                            'last_login'=>date('Y-m-d H:i:s'),
                            // 'fcm_token'=> $request->fcm_token,
                            ]);

                if ($user->is_active==0){
                    return  $this->returnError([helperTrans('api.User Is Blocked')]);
                }

                // $user->update([]);
                $user->token = $token;

                return $this->returnData(AuthUserResource::make($user),[helperTrans('api.login successfully')]);
            } else {

                return $this->returnErrorValidation(collect(['Invalid credentials.']), 403);
            }

            if (!$user) {
                return $this->returnError([helperTrans('api.Invalid Credentials')], 401);
            }

        } catch(Exception $e){
           return  $this->errorResponse($e->getMessage());
        }
    }


// ******************************************************************
// ******************************************************************

    public function logout()
    {
         try {

                JWTAuth::invalidate(JWTAuth::getToken());

                $token = JWTAuth::getToken();

                JWTAuth::parseToken($token)->invalidate(true);

                return $this->returnSuccessMessage([helperTrans('api.logout successfully')]);
            }catch (JWTException $e) {
                return $this->returnExceptionError($e);
            }
    }

    // ******************************************************************
// ******************************************************************

    public function forgetPassword(Request $request){
        try {
            $login_value = $request->email;
            $rules = [];

            if (filter_var($login_value, FILTER_VALIDATE_EMAIL)) {
                $rules['email'] = 'required|email|exists:users,email';
            } elseif (preg_match('/^0[0-9]{10}$/', $login_value)) {
                $rules['phone'] = 'required|regex:/^0[0-9]{10}$/|exists:users,phone';
            } else {
                return $this->returnErrorValidation(collect([helperTrans('api.Invalid email or phone number must be 11 digits starting with 0')]), 403);
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = User::where('email', $login_value)->orWhere('phone', $login_value)->first();

            if (!$user) {
                return $this->returnError([helperTrans('api.Invalid Credentials')], 401);
            }

            if ($user->is_active==0){
                return $this->returnError([helperTrans('api.User Is Blocked')]);
            }

            // Generate OTP
            // $otp = rand(1000, 9999);
            $otp = 1234;
            // Update user with OTP
            $user->update(['otp' => $otp]);

            // Send OTP via email if email exists
            // if ($user->email) {
            //     try {
            //         Mail::to($user->email)->send(new OtpMail($otp, $user));
            //     } catch (\Exception $e) {
            //         Log::error('Failed to send OTP email: ' . $e->getMessage());
            //         return $this->returnError(['message' => 'Failed to send OTP email. Please try again.'], 500);
            //     }
            // }

            return $this->returnSuccessMessage(['message' => 'OTP code sent successfully']);

        } catch(Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // ******************************************************************
// ******************************************************************

    public function validateOtp(Request $request){

        try{

            $login_value = $request->email;

            $rules = ['otp' => 'required'];

            if (filter_var($login_value, FILTER_VALIDATE_EMAIL)) {

                $rules['email'] = 'required|email|exists:users,email';

            } elseif (preg_match('/^\+?[0-9]{10,15}$/', $login_value)) {

                $rules['phone'] = 'required|exists:users,phone';

            } else {

                return $this->returnErrorValidation(collect([helperTrans('api.Invalid email or phone')]), 403);
            }


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            if (filter_var($login_value, FILTER_VALIDATE_EMAIL)) {
                $credentials = $request->only('email');
            } else {
                $credentials = [
                    'phone' => $login_value,
                ];
            }

            $user = User::where('email', $login_value)->orWhere('phone', $login_value)->first();

            if (!$user) {
                return $this->returnError([helperTrans('api.Invalid Credentials')], 401);
            }


            if ($user->is_active==0){
                return  $this->returnError([helperTrans('api.User Is Blocked')]);
            }

            if($request->otp == $user->otp){
                return $this->returnSuccessMessage(['message' => 'otp is valid , go for update password']);
            }

            return $this->returnError(['message' => 'otp is wrong'],403);

        } catch(Exception $e){

             return  $this->errorResponse($e->getMessage());

        }
    }

// ******************************************************************
// ******************************************************************
        public function updatePassword(Request $request){

            try{

                $login_value = $request->email;
                $rules = [
                            'password' => 'required',
                            'otp' => 'required',
                            'confirm_password' => 'required|same:password',

                        ];

                if (filter_var($login_value, FILTER_VALIDATE_EMAIL)) {

                    $rules['email'] = 'required|email|exists:users,email';

                } elseif (preg_match('/^\+?[0-9]{10,15}$/', $login_value)) {

                    $rules['phone'] = 'required|exists:users,phone';

                } else {

                    return $this->returnErrorValidation(collect([helperTrans('api.Invalid email or phone')]), 403);
                }


                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
                }

                if (filter_var($login_value, FILTER_VALIDATE_EMAIL)) {
                    $credentials = $request->only('email');
                } else {
                    $credentials = [
                        'phone' => $login_value,
                    ];
                }

                $user = User::where('email', $login_value)->orWhere('phone', $login_value)->first();

                if (!$user) {
                    return $this->returnError([helperTrans('api.Invalid Credentials')], 401);
                }


                if ($user->is_active==0){
                    return  $this->returnError([helperTrans('api.User Is Blocked')]);
                }

                if($request->otp != $user->otp){
                    return $this->returnError(['message' => 'otp is wrong , pleas tru again'],403);
                }

                $user->update([
                            'password'=>bcrypt($request->password),
                            'otp'=>null,
                        ]);

                $token = Auth::guard('user')->fromUser($user);

                if ($token) {

                    $user->token = $token;

                    return $this->returnData(AuthUserResource::make($user),[helperTrans('api.login successfully')]);
                }

            } catch(Exception $e){

                return  $this->errorResponse($e->getMessage());

            }
        }
// ******************************************************************
// ******************************************************************
    public function deleteAcount(){

            try{

                $user =userApi()->user();

                if ($user) {

                    $this->logout();

                    $user->update(['is_active'=>0]);

                    // dd($user);

                    return $this->returnSuccessMessage(['message' => 'acount deleted successfully']);
                }

            } catch(Exception $e){

                return  $this->errorResponse($e->getMessage());

            }
    }
// *********************************************************************
// *********************************************************************

    public function updateFcmToken(Request $request)
    {
        try {
            $request->validate([
                'fcm_token' => 'required|string'
            ]);

            $user = userApi()->user();
            if (!$user) {
                return $this->returnError([helperTrans('api.Unauthorized')], 401);
            }

            // Get current tokens or initialize empty array
            $tokens = $user->fcm_token ? json_decode($user->fcm_token, true) : [];

            // Add new token if it doesn't exist
            if (!in_array($request->fcm_token, $tokens)) {
                $tokens[] = $request->fcm_token;
            }

            $user->update([
                'fcm_token' => json_encode($tokens)
            ]);

            return $this->returnSuccessMessage([helperTrans('api.FCM token updated successfully')]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

}
