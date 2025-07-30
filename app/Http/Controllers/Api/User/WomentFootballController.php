<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;

use App\Http\Resources\WomenFootballResource;
use App\Http\Resources\WomenFootballCategoryResource;

use App\Models\WomenFootball;
use App\Models\WomenFootballCategory;
use App\Models\WomenFootballAction;
use App\Models\UserAcademy;

use Illuminate\Http\Request;
use App\Http\Traits\Api_Trait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class WomentFootballController extends Controller
{
    use Api_Trait;

    // ***********************************************************************
    // ***********************************************************************
    public function get_women_football_Category(){

        try{

            $categories = WomenFootballCategory::get();

            return $this->returnData(WomenFootballCategoryResource::collection($categories), [helperTrans('api.womens footballe categoires Data')]);

        }catch(Exception $e){

            return $this->returnExceptionError($e);
        }

    }
    
    // ***********************************************************************
    // ***********************************************************************
    public function get_woment_football_posts($categoryId)
    
    {
        try{

            $types = WomenFootball::where('women_football_category_id',$categoryId)->get();

            return $this->returnData(WomenFootballResource::collection($types), [helperTrans('api.womens footballe Data')]);

        }catch(Exception $e){

            return $this->returnExceptionError($e);
        }
    }
    // ***********************************************************************
    // ***********************************************************************
    public function get_woment_football_posts_details($postId)
    
    {
        try{

            $post = WomenFootball::find($postId);

            if(!$post){
                return $this->returnError(helperTrans('api.womens footballe post not found'));
            }

            return $this->returnData(WomenFootballResource::make($post), [helperTrans('api.womens footballe datils')]);

        }catch(Exception $e){

            return $this->returnExceptionError($e);
        }
    }
// ***********************************************************************
// ***********************************************************************
    public function toggle_liked_women_football_post($womenFootballId){

        try{

            $validator = Validator::make(['women_football_id' => $womenFootballId], [
                'women_football_id' =>'required|exists:women_footballs,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            // $user = userApi()->user();  

            DB::beginTransaction();

            $message = WomenFootballAction::toggleAction($womenFootballId,1) == 1 ? 'Liked' : 'Unliked';


            DB::commit();

            return $this->returnSuccessMessage(helperTrans("api.$message Successfully")); // Single resource instance

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }

    // ************************************************
    // ************************************************ 
        

    // ************************************************
    // ************************************************


}
