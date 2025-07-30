<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoteResource;
use App\Http\Traits\Api_Trait;

use App\Models\Vote;
use App\Models\VoteChoice;
use App\Models\VoteChoicesRate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    use Api_Trait;

    //
    public function get_votes()
    {
        try{

            $votes = Vote::with('choices','choices.rates')->get();

            return $this->returnData(VoteResource::collection($votes), [helperTrans('api.votes Data')]);

        }catch(Exception $e){

        }
    }


  // ************************************************************************
  // ************************************************************************
    public function store_vote_choice(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'vote_id' =>'required|exists:votes,id',
                'choice_id' =>'required|exists:vote_choices,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();  

            DB::beginTransaction();

            $vote = VoteChoicesRate::UpdateOrCreate([
                                    'user_id' => $user->id,
                                    'vote_id' => $request->vote_id,
                                    ],
                                    [
                                    'vote_choices_id' => $request->choice_id,
                                    ]
                                );
            if($vote->wasRecentlyCreated) {

                increment_User_points('vote',dynamicSetting('vote_points'));

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
