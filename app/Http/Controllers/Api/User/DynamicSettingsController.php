<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\DynamicSettingResource;
use App\Http\Traits\Api_Trait;
use App\Models\DynamicSetting;
use Exception;

class DynamicSettingsController extends Controller
{
    use Api_Trait;

    public function index()
    {
        try {
            $settings = DynamicSetting::orderBy('value', 'desc')->get();
                        return $this->returnData(DynamicSettingResource::collection($settings), [helperTrans('api.dynamic settings Data')]);
        } catch(Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
}
