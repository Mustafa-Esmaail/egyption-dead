<?php

namespace App\Http\Controllers\Api\Provider\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthProviderResource;
use App\Http\Traits\Api_Trait;
use App\Http\Traits\Upload_Files;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use Api_Trait,Upload_Files;
    //
    public function login(Request $request){
        $validator = Validator::make($request->all(),
            [
                'phone' => 'required',
                'password' => 'required',
            ], []);
        if ($validator->fails()) {
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
        }


        $requestData = $request->all();


        if ($token = provider()->attempt($requestData, 1)) {

            $provider = provider()->user();
            if ($provider->status=='pending'){
                return $this->returnError(['api.Waiting for approval']);
            }
            $provider->token = $token;

            return $this->returnData(AuthProviderResource::make($provider),[helperTrans('api.login successfully')]);
        }



        return $this->returnError([helperTrans('api.No branch was found with this credentials')], 422);
    }

    public function signup(Request $request){
        $validator = Validator::make($request->all(),
            [
                'city_id'=>'required|exists:cities,id',
                'phone'=>'required|unique:providers,phone',
                'address'=>'nullable|string',
                'password'=>'required|string',
                'service_id'=>'required|exists:services,id',
                'type'=>'required|in:center,transfer',
                'role'=>'required|in:team,owner',
                'provider_id' => 'required_if:role,team|nullable|exists:providers,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'name'=>'required|string',


            ], []);
        if ($validator->fails()) {
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
        }
        $image=null;
        $password = bcrypt($request->password);
        if ($request->image)
            $image = $this->uploadFiles('providers', $request->file('image'), null);


        $store=Provider::create([

            'city_id'=>$request->city_id,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'password'=>$password,
            'service_id'=>$request->service_id,
            'type'=>$request->type,
            'role'=>$request->role,
            'provider_id'=>$request->provider_id,
            'image' => $image,
            'name'=>$request->name,

        ]);




   return  $this->returnSuccessMessage(['api.signup successfully']);
    }
}
