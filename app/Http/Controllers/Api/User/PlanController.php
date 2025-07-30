<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPlanResource;
use App\Http\Resources\UserPalnCommentResource;
use App\Http\Resources\TeamPlayerResource;
use App\Http\Resources\PlanResource;
use App\Http\Resources\CustomeUserPlanResource;
use App\Http\Traits\Api_Trait;

use App\Models\Plan;
use App\Models\TeamPlayer;
use App\Models\UserPlan;
use App\Models\UserPlanComment;
use App\Models\UserPlanAction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\Upload_Files;

class PlanController extends Controller
{
    use Api_Trait,Upload_Files;

    //
    public function store_plan(Request $request)
    {
        try{

         
            $rules = [
                'type' => 'required|in:1,2', 
                'data' => 'required|array|size:11',
                'team_id' => 'required|exists:teams,id',
                'data.*.player_id' => [
                    'required',
                    'exists:team_players,id',
                    function ($attribute, $value, $fail) use ($request) {
                        $teamId = $request->team_id;
                        $isValid = \DB::table('team_players')
                            ->where('id', $value)
                            ->where('team_id', $teamId)
                            ->exists();
            
                        if (!$isValid) {
                            $message ="The player with ID {$value} is not related to the team with ID {$teamId}.";
                            
                            $fail($message);
                        }

                        $playerIds = array_column($request->data, 'player_id');
                        if (count(array_keys($playerIds, $value)) > 1) {
                            $message ="The player with ID {$value} is duplicated in the data array.";
                            $fail($message);
                        }
                    },
                ],
                'data.*.position_x' => 'required', 
                'data.*.position_y' => 'required',
            ];

            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
            }

            $reqData =$request->all();

            $user = userApi()->user();

            // dd($user->favouriteTeam);
            if(!$user){
                return $this->returnError(helperTrans('api.user not Auth'),403);
            }

            DB::beginTransaction();

            $teamId = $user->favouriteTeam()->id ?? null;

            if(!$teamId){
                return $this->returnError(helperTrans('api.please select favourite team'),403);
            }
            // dd();
            $userPlan = UserPlan::where('user_id',$user->id)
                                ->where('team_id',$teamId)
                                ->orderBy('id','desc')->first();
            if($userPlan){

                Plan::where('plan_id',$userPlan->id)->delete();

            }else{

                $userPlan = UserPlan::create([
                    'user_id'=>$user->id,
                    'team_id'=>$teamId ?? $request->team_id,
                    'type'=>$request->type,//1 = Basic plan - 2 = Reserve plan
                ]);
            }                    

           

            $plans = $request->data;
            $planData=[];

            $basicPlayerID = [];
            foreach($plans as $plan){

                $basicPlayerID[]=$plan['player_id'];

                $planData[]=[
                        'plan_id'=> $userPlan->id,
                        'player_id'=>$plan['player_id'],
                        'postion_x'=>$plan['position_x'],
                        'postion_y'=>$plan['position_y'],
                    ];
            }

            $reserve_players = TeamPlayer::where('team_id',$request->team_id)
                                            ->whereNotIn('id',$basicPlayerID)
                                            ->get();

            foreach($reserve_players as $player){
                $planData[]=[
                        'plan_id'=> $userPlan->id,
                        'player_id'=>$player->id,
                        'postion_x'=>0,
                        'postion_y'=>0,
                    ];
            }
            // dd($planData);
            $plans = Plan::insert($planData);

            increment_User_points('Add Plan',dynamicSetting('add_plan'));

            DB::commit();
            $userPlan->load('plans');
            return $this->returnData(UserPlanResource::make($userPlan), [helperTrans('api.plan saved successfuly')]);

        }catch(Exception $e){

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }

    // *******************************************************************
    // *******************************************************************
    public function get_plans()
    {
        try{

            $plans = UserPlan::with(['plans' => function ($query) {
                                    $query->where('postion_x', '!=', 0);
                                    $query->where('postion_y', '!=', 0);
                                },'comments'])
                                ->whereHas('plans', function ($query) {
                                    $query->where('postion_y', '!=', 0);
                                    $query->where('postion_x', '!=', 0);
                                })
                                ->orderBy('id', 'desc')
                                ->get();

            return $this->returnData(UserPlanResource::collection($plans), [helperTrans('api.All plans')]);

        }catch(Exception $e){

        }
    }
    // ***********************************************************
    // ***********************************************************
    public function get_user_plan()
    {
        try{

            $user = userApi()->user();
            // $teamId = $user->club_id;
           $teamId = $user->favouriteTeam()->id;

            $plans = UserPlan::where('user_id', $user->id)
            ->where('team_id', $teamId)
            ->with('plans')
            ->latest('id')
            ->first();

                    
            if(!$plans){
            
              $players = TeamPlayer::where('team_id',$teamId)->get();
                $staticData =  [
                    'id'=>null,
                   'type'=>null,
                   'user_name'=>null,
                   'create_date'=> null,
                   'comments'=>null,
                   'count_comments'=>null,
                   'count_likes'=>null,
                   'is_plan'=>false,
                ];
              $allData = array_merge($staticData,['player'=> CustomeUserPlanResource::collection($players)]);
                // dd($teamId,$players);
              return $this->returnData($allData,
               [helperTrans('api.no plans , players data')]);
            }

            
            return $this->returnData(UserPlanResource::make($plans), [helperTrans('api.user plans')]);

        }catch(Exception $e){
            
            return $this->returnExceptionError($e);

        }
    }
    // *********************************************************************
    // *********************************************************************
    
    public function add_comment_to_plan(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'user_plan_id' =>'required|exists:user_plans,id',
                'comment' =>'required|max:500',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();  

            DB::beginTransaction();

            $comment = UserPlanComment::create(
                [
                    'user_id' => $user->id,
                    'user_plan_id' => $request->user_plan_id,
                    'comment' => trim($request->comment),
                ]
            );

            if ($comment->wasRecentlyCreated) {

                increment_User_points('add comment on plan',dynamicSetting('comment_points'));

                $message = helperTrans("api.Comment created successfully.");
            } else {
                $message = helperTrans("api.Comment updated successfully.");
            }
        
            DB::commit();

            return $this->returnData(UserPalnCommentResource::make($comment),$message); 

        }catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }      // *********************************************************************
    // *********************************************************************
    public function edit_plan_comment(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'comment_id' =>'required|exists:user_plan_comments,id',
                'comment' =>'required|max:500',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();  

            DB::beginTransaction();

            $comment = UserPlanComment::where('user_id' ,$user->id,)
                                        ->find($request->comment_id);
            if(!$comment){
                return $this->returnError(helperTrans('api.Comment not found'),404);
            }
            $comment->update(['comment' => trim($request->comment)]);

            $message = helperTrans("api.Comment updated successfully.");
        
            DB::commit();

            return $this->returnData(UserPalnCommentResource::make($comment),$message); 

        }catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }   

    // ********************************************************
    // ********************************************************
    public function toggle_liked_plan($id){

        try{

            $validator = Validator::make(['user_plan_id' => $id], [
                'user_plan_id' =>'required|exists:user_plans,id',
            ]);

            if ($validator->fails()) {

                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();  

            DB::beginTransaction();

            $planAction = UserPlanAction::where([
                'user_id' => $user->id,
                'user_plan_id' => $id,
                'action_type' => 1,
            ])->first();
            
            if ($planAction) {

                $planAction->delete();
                
                increment_User_points('unliked plan',dynamicSetting('like_points'),0);
                $message = 'unliked';
            } else {
                // Create a new record if it doesn't exist
                UserPlanAction::create([
                    'user_id' => $user->id,
                    'user_plan_id' => $id,
                    'action_type' => 1,
                    'action' => 1, // Default to liked
                ]);
                increment_User_points('liked plan',dynamicSetting('like_points'));
                
                $message = 'liked';
            }


            DB::commit();

            return $this->returnSuccessMessage([helperTrans("api.$message Successfully")]); // Single resource instance

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }
    // ********************************************************
    // ********************************************************

    public function remove_plan_comment($id){

        try{

            $validator = Validator::make(['comment_id' => $id], [
                'comment_id' =>'required|exists:user_plan_comments,id',
            ]);

            if ($validator->fails()) {

                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();  

            DB::beginTransaction();

            $planAction = UserPlanComment::where([
                'user_id' => $user->id,
                'user_plan_id' => $id,
                'action_type' => 1,
            ])->first();
            
            if ($planAction) {

                $planAction->delete();

                increment_User_points('delete comment from plan',dynamicSetting('comment_points'),0);

                DB::commit();
    
                return $this->returnSuccessMessage([helperTrans("api.comment deleted Successfully")]); // Single resource instance
            }



        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    } 
      // ********************************************************
    // ********************************************************
    

}
