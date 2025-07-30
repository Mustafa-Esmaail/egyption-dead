<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderPriceResource;
use App\Http\Resources\ProviderResource;
use App\Http\Traits\Api_Trait;
use App\Http\Traits\Upload_Files;
use App\Models\Provider;
use App\Models\Provider_price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    //
    use Api_Trait,Upload_Files;
    public function profile(Request $request){

        $authProvider = auth('provider')->user();
        $provider=Provider::with(['city','service','provider'])->find($authProvider->id);

        return $this->returnData(ProviderResource::make($provider), [helperTrans('api.Profile Data')]);

    }

    public function update_profile(Request $request){
        $provider = auth('provider')->user();

        $validator = Validator::make($request->all(),
            [
                'city_id'=>'required|exists:cities,id',
                'phone'=>'required|unique:providers,phone,'.$provider->id,
                'address'=>'nullable|string',
                'password'=>'nullable|string',
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
        $image=$provider->image??null;
        $password=$provider->password;
        if ($request->image) {
            $image = $this->uploadFiles('providers', $request->file('image'), 'yes');
        }
        if ($request->password)
        {
            $password = bcrypt($request->password);
        }

        $provider->update([
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
        return $this->returnSuccessMessage([helperTrans('api.Profile Updated')]);

    }

    public function add_provider_price(Request $request)
    {
        $provider = auth('provider')->user();

        $validator = Validator::make($request->all(),
            [
                'model_id'=>'nullable|exists:models,id',
                'make_id'=>'nullable|exists:makes,id',
                'type_id'=>'required|exists:types,id',
                'price'=>'required|numeric',


            ], []);
        if ($validator->fails()) {
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
        }

        $provider_price=Provider_price::where('provider_id',$provider->id)->where('model_id',$request->model_id)->where('make_id',$request->make_id)->where('type_id',$request->type_id)->first();

        if($provider_price){
            $provider_price->update(['price'=>$request->price]);
            return $this->returnSuccessMessage([helperTrans('api.Price Updated')]);
        }

        Provider_price::create([
            'model_id'=>$request->model_id,
            'make_id'=>$request->make_id,
            'type_id'=>$request->type_id,
            'price'=>$request->price,
            'provider_id'=>$provider->id,
        ]);

        return $this->returnSuccessMessage([helperTrans('api.Price Added')]);

    }

    public function provider_prices(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'provider_id'=>'required|exists:providers,id',

            ], []);
        if ($validator->fails()) {
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
        }

        $provider_prices=Provider_price::with(['type','make','model'])->where('provider_id',$request->provider_id)->get();

        return  $this->returnData(ProviderPriceResource::collection($provider_prices),[helperTrans('api.Provider Price')]);

    }



    public function add_team(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'required|string',
            'phone'=>'required|unique:providers,phone',
            'role'=>'required|in:team,owner',

        ], []);
        if ($validator->fails()) {
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
        }

        $provider = auth('provider')->user();

        Provider::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'role'=>$request->role,
            'city_id'=>$provider->city_id,
            'address'=>$provider->address,
            'password'=>$provider->password,
            'service_id'=>$provider->service_id,
            'type'=>$provider->type,
            'provider_id'=>$provider->id,
            'image' => $provider->image,
        ]);


        return $this->returnSuccessMessage([helperTrans('api.Team Added')]);
    }


}
