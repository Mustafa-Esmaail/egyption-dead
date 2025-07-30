<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamPlayerResource;
use App\Http\Resources\TeamGroupResource;
use App\Http\Traits\Api_Trait;
use App\Http\Traits\Upload_Files;

use App\Models\UserFavouriteTeamAndPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\TeamGroupRequest;
use App\Models\Team;
use App\Models\TeamPlayer;
use App\Models\TeamGroup;
use App\Models\UserGroup;
use App\Models\Notification;
use App\Services\FirebaseNotificationService;
use Exception;

class TeamPlayerController extends Controller
{
    use Api_Trait, Upload_Files;

    //
    public function get_teams()
    {
        try {

            $teams = Team::get();

            return $this->returnData(TeamResource::collection($teams), [helperTrans('api.teams Data')]);
        } catch (Exception $e) {
        }
    }

    // *******************************************************************
    // *******************************************************************
    public function get_players_of_team($teamId)
    {
        try {

            $teams = TeamPlayer::where('team_id', $teamId)->get();

            return $this->returnData(TeamPlayerResource::collection($teams), [helperTrans('api.players Data')]);
        } catch (Exception $e) {
        }
    }

    // ************************************************
    // ************************************************

    public function add_team_group(Request $request)
    {

        try {

            $rules = [
                'team_id' => 'required|exists:teams,id',
                'title' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();
            if (!$user) {
                return $this->returnError([helperTrans('api.user not found')]);
            }
            $data = $request->only('team_id', 'image', 'title');

            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $data['image'] = $this->uploadFiles('team-groups', $image);
            }

            $data['user_id'] = $user->id;
            // $data['added_by'] = 'user';.

            DB::beginTransaction();

            $row = TeamGroup::create($data);

            $notificationData = [
                'action_id' => $row->id,
                'model_name' => 'TeamGroup',
                'message' => helperTrans('api.team group added Successfuly,waiting for approve'),
            ];

            Notification::storeNotification($notificationData);
            DB::commit();
            return $this->returnSuccessMessage(helperTrans('api.team group added Successfuly,waiting for approve'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }
    // ************************************************
    // ************************************************

    public function update_team_group(Request $request)
    {

        try {

            $rules = [
                'id' => 'required|exists:team_groups,id',
                'team_id' => 'required|exists:teams,id',
                'title' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            if (!$user) {
                return $this->returnError([helperTrans('api.user not found')]);
            }

            $teamGroup = TeamGroup::find($request->id);
            // dd($teamGroup);

            if ($teamGroup && $teamGroup->user_id != $user->id) {

                return $this->returnError([helperTrans('api.this group not belong to you')]);
            }

            $data = $request->only('team_id', 'image', 'title');

            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $data['image'] = $this->uploadFiles('team-groups', $image, 'yes');
            }

            $data['status'] = '1';
            // $data['added_by'] = 'user';
            DB::beginTransaction();


            $row = $teamGroup->update($data);

            $notificationData = [
                'action_id' => $teamGroup->id,
                'model_name' => 'TeamGroup',
                'message' => helperTrans('api.team group updated successfuly,waiting for approve'),
            ];

            Notification::storeNotification($notificationData);
            DB::commit();
            return $this->returnSuccessMessage(helperTrans('api.team group updated Successfuly,waiting for approve'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }

    // ************************************************
    // ************************************************

    public function delete_team_group(Request $request)
    {

        try {

            $rules = [

                'team_group_id' => 'required|exists:team_groups,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            if (!$user) {
                return $this->returnError([helperTrans('api.user not found')]);
            }

            $teamGroup = TeamGroup::find($request->team_group_id);
            // dd($teamGroup);

            if ($teamGroup && $teamGroup->user_id != $user->id) {

                return $this->returnError([helperTrans('api.this group not belong to you')]);
            }

            $row = $teamGroup->delete();

            // DB::commit();
            return $this->returnSuccessMessage(helperTrans('api.team group deleted Successfuly'));
        } catch (Exception $e) {
            // DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }

    // *******************************************************
    // *******************************************************
    public function get_team_groups(Request $request, $teamId)
    {

        try {

            // dd($request);
            $rules = [
                'team_id' => 'required|exists:teams,id',
            ];

            $validator = Validator::make(['team_id' => $teamId], $rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $groups  = TeamGroup::where('team_id', $teamId)->where('status', 2)->get();;
            DB::commit();
            return $this->returnData(TeamGroupResource::collection($groups), helperTrans('api.team group'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }
    // add team group
    // *******************************************************
    // *******************************************************
    public function get_user_team_groups($teamId)
    {

        try {

            $user = userApi()->user();

            if (!$user) {
                return $this->returnError([helperTrans('api.user not found')]);
            }

            $teamGroups = UserGroup::with('teamGroup')
                ->where('user_id', $user->id)
                ->get()
                ->pluck('teamGroup')
                ->filter(fn($group) => $group && $group->team_id == $teamId)
                ->values();

            return $this->returnData(
                TeamGroupResource::collection($teamGroups),
                helperTrans('api.team group')
            );
        } catch (Exception $e) {
            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }
    //  add_user_group
    public function add_user_group(Request $request){
        try{
            $rules = [
                'team_group_id' => 'required|exists:team_groups,id',
            ];
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
            }
            $user = userApi()->user();
            if (!$user) {
                return $this->returnError([helperTrans('api.user not found')]);
            }

            DB::beginTransaction();

            // Create user group relationship
            UserGroup::create([
                'user_id' => $user->id,
                'team_group_id' => $request->team_group_id,
            ]);

            // Get the team group
            $teamGroup = TeamGroup::findOrFail($request->team_group_id);

            // Store database notification
            $notificationData = [
                'action_id' => $teamGroup->id,
                'model_name' => 'team_group',
                'user_id' => $user->id,
                'message' => helperTrans('api.You have joined the team group') . ': ' . $teamGroup->title,
            ];
            Notification::storeNotification($notificationData);

            // Send push notification
            $firebaseService = app(FirebaseNotificationService::class);
            $firebaseService->sendToUser(
                $user,
                'Joined Team Group',
                'You have joined the team group: ' . $teamGroup->title,
                [
                    'team_group_id' => $teamGroup->id,
                    'team_group_title' => $teamGroup->title,
                    'type' => 'team_group_joined'
                ]
            );

            DB::commit();
            return $this->returnSuccessMessage(helperTrans('api.team group added to user'));
        }catch(Exception $e){
            DB::rollback();
            return $this->returnExceptionError($e);
        }
    }

    // *******************************************************
    // *******************************************************
    public function add_user_team(Request $request)
    {

        try {

            $rules = [
                'team_id' => 'required|exists:teams,id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();
            if (!$user) {
                return $this->returnError([helperTrans('api.user not found')]);
            }
            UserFavouriteTeamAndPlayer::create([
                'user_id' => $user->id,
                'foriegn_key' => $request->team_id,
                'id_belong_to' => 1 // if you're filtering by this
            ]);
            return $this->returnSuccessMessage(helperTrans('api.team added to favourite'));
        } catch (Exception $e) {
            return $this->returnExceptionError($e);
        }
    }


    public function delete_user_team(Request $request)
    {
        try {
            $rules = [
                'team_id' => 'required|exists:teams,id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }
            $user = userApi()->user();
            if (!$user) {
                return $this->returnError([helperTrans('api.user not found')]);
            }
            UserFavouriteTeamAndPlayer::where('user_id', $user->id)->where('foriegn_key', $request->team_id)->delete();
            return $this->returnSuccessMessage(helperTrans('api.team removed from favourite'));
        } catch (Exception $e) {
            return $this->returnExceptionError($e);
        }
    }
}
