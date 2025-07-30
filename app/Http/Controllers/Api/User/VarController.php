<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\VarResource;
use App\Http\Traits\Api_Trait;

use App\Models\VarTab;
use App\Models\VarChoose;
use App\Models\VarChoicesRate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VarController extends Controller
{
    use Api_Trait;

    //
    public function get_vars()
    {
        try{


            $vars = VarTab::with('images','choices')->get();

            return $this->returnData(VarResource::collection($vars), [helperTrans('api.vars Data')]);

        }catch(Exception $e){

        }
    }


  // ************************************************************************
  // ************************************************************************
    public function store_var_choice(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'var_id' =>'required|exists:vars,id',
                'choice_id' =>'required|exists:var_chooses,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();  

            DB::beginTransaction();

            $var = VarChoicesRate::UpdateOrCreate([
                                    'user_id' => $user->id,
                                    'var_id' => $request->var_id,
                                    ],
                                    [
                                    'var_choices_id' => $request->choice_id,
                                    ]
                                );
            if($var->wasRecentlyCreated) {

                increment_User_points('vote on var',dynamicSetting('var_points'));

            }
            DB::commit();

            return $this->returnSuccessMessage(helperTrans("api.Choice Selected Successsfuly")); // Single resource instance

        }catch (\Exception $e) {

            DB::rollBack();

            return $this->returnError($e->getMessage(), 500);
        }
    }   
    // ********************************************************
    // ********************************************************
    

}
