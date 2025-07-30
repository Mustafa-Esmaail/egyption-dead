<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamPlayerResource;
use App\Http\Traits\Api_Trait;
use App\Http\Traits\Upload_Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\UserFavouriteTeamAndPlayer;


class UserController extends Controller
{
    use Api_Trait,Upload_Files;

    public function profile(Request $request){

        $user = userApi()->user();

        return $this->returnData(UserResource::make($user), [helperTrans('api.Profile Data')]);
    }
    // *********************************************************
    // *********************************************************

    public function update_profile(Request $request){

        $user = userApi()->user();

        $validator = Validator::make($request->all(),
            [
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'email' => [
                                'required',
                                'email',
                                Rule::unique('users', 'email')->ignore($user->id),
                            ],

                'type' => 'required|in:male,female',
                'team_id' => 'required|exists:teams,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            ], []);

        if ($validator->fails()){
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
        }

        // dd($request->file('image'));
        $image= $user->image ?? null;

        if ($request->has('image')) {

            $image = $this->uploadFiles('user', $request->file('image'), 'yes');
            // dd($image);

        }

            // dd($image);
        $this->add_user_favourite_team($request->team_id);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'type' => $request->type,
            'email' =>$request->email,
            // 'club_id' =>$request->team_id,
            'image' => $image,
        ]);

        $user->load('teams');
        return $this->returnData(UserResource::make($user), [helperTrans('api.Profile Updated Data')]);

    }

// ****************************************************************
// ****************************************************************
    public function add_user_favourite_team($id){

        try{

            $validator = Validator::make(['team_id' => $id], [
                'team_id' =>'required|exists:teams,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

                $favoriteTeam = UserFavouriteTeamAndPlayer::updateOrCreate(
                    [
                        'user_id' => $user->id, // Matching condition
                        'id_belong_to' => 1,
                    ],
                    [
                        'foriegn_key' => $id,
                    ]
                );

                if ($favoriteTeam->wasRecentlyCreated) {

                    increment_User_points('select Favorite team',dynamicSetting('favourite_team'));

                }

            DB::commit();



            // dd($share->countshares());
            return $this->returnSuccessMessage([helperTrans("api.Team add to favoirte Successfully")]);

            // Single resource instance

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }
// ****************************************************************
// ****************************************************************
    public function add_user_favourite_player($id){

        try{

            $validator = Validator::make(['player_id' => $id], [
                'player_id' =>'required|exists:team_players,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

                $favoriteplayer = UserFavouriteTeamAndPlayer::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'id_belong_to' => 2,
                    ],
                    [
                        'foriegn_key' => $id,
                    ]
                );

                if ($favoriteplayer->wasRecentlyCreated) {

                    increment_User_points('select Favorite Player',dynamicSetting('favourite_player'));

                }

            DB::commit();

            // dd($share->countshares());
            return $this->returnSuccessMessage([helperTrans("api.player add to favoirte Successfully")]);

            // Single resource instance

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }

// ***********************************************************
// ***********************************************************
    public function get_user_favourite_team(){

        try{

            $user = userApi()->user();

            $team = $user->teams->first();

            return $this->returnData(TeamResource::make($team),[helperTrans('api.user favourite team Data')]);

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }


// ***********************************************************
// ***********************************************************
    public function get_user_favourite_player(){

        try{

            $user = userApi()->user();
            $player = $user->player->first();
            return $this->returnData(TeamPlayerResource::make($player),[helperTrans('api.user favourite player Data')]);

        }catch (\Exception $e) {
            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }// ***********************************************************
// ***********************************************************







}
