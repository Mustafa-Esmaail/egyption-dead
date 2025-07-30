<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Http\Traits\Api_Trait;
use App\Models\DynamicSetting;
use App\Models\Setting;
use App\Models\UserAcademy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    use Api_Trait;



    public function get_settings()
    {
        try{

            $settings = Setting::firstOrCreate();

            return $this->returnData(SettingResource::make($settings), [helperTrans('api.settings ')]);

        }catch(Exception $e){

        }
    }


}
