<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\AreaResource;
use App\Http\Traits\Api_Trait;
use App\Models\Area;
use App\Models\Country;
use App\Models\City;
use App\Models\VoteChoicesRate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    use Api_Trait;

    //
    public function get_countries()
    {
        try{

            $countries = Country::get();

            return $this->returnData(CountryResource::collection($countries), [helperTrans('api.countries Data')]);

        }catch(Exception $e){

        }
    }


  // ************************************************************************
  // ************************************************************************
    public function get_cities($countryId){

        try{

            $validator = Validator::make(['coutnry_id'=>$countryId], [
                'coutnry_id' =>'required|exists:countries,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $cities = City::where('country_id',$countryId)->get();

            return $this->returnData(CityResource::collection($cities), [helperTrans('api.cities  Data')]);


        }catch (\Exception $e) {

            DB::rollBack();

            return $this->returnError($e->getMessage(), 500);
        }
    }
    // ********************************************************
    // ********************************************************

    public function get_areas($cityId){

            try{

                $validator = Validator::make(['city_id'=>$cityId], [
                    'city_id' =>'required|exists:cities,id',
                ]);

                if ($validator->fails()) {
                    return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
                }

                $areas = Area::with('country','city')->where('city_id',$cityId)->get();

                return $this->returnData(AreaResource::collection($areas), [helperTrans('api.areas  Data')]);


            }catch (\Exception $e) {

                DB::rollBack();

                return $this->returnError($e->getMessage(), 500);
            }
        }

}
