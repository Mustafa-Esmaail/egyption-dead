<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AwardResource;
use App\Http\Resources\TeamPlayerResource;
use App\Http\Traits\Api_Trait;

use App\Models\Award;
use App\Models\TeamPlayer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class AwardsController extends Controller
{
    use Api_Trait;

    //
    public function get_awards()
    {
        try {
            $user = userApi()->user();

            $awards = Award::get()->map(function ($award) use ($user) {
                $award->can_redeem = $user->points >= $award->points;
                return $award;
            });

            return $this->returnData(AwardResource::collection($awards), [helperTrans('api.awards Data')]);

        } catch(Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    // *******************************************************************
    // *******************************************************************
    public function get_players_of_team($teamId)
    {
        try{

            $teams = TeamPlayer::where('team_id',$teamId)->get();

            return $this->returnData(TeamPlayerResource::collection($teams), [helperTrans('api.players Data')]);

        }catch(Exception $e){

        }
    }

    // ************************************************
    // ************************************************




}
