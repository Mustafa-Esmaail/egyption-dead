<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserTalantCommentResource;
use App\Http\Resources\UserTalantResource;
use App\Http\Traits\Api_Trait;
use App\Http\Traits\Upload_Files;
use App\Models\Notification;
use App\Models\TalantRate;
use App\Models\User;
use App\Models\UserTalant;
use App\Models\UserTalantAction;
use App\Models\UserTalantComment;
use App\Services\FirebaseNotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserTalantController extends Controller
{
    use Api_Trait, Upload_Files;

    //
    public function get_talants(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'filter_by' => 'nullable|in:latest,popular',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $talants = UserTalant::with(['comments' => function ($query) {
                $query->latest();
            }, 'rates'])
                ->where('status', 2)
                ->withAvg('rates', 'rate');

            // Check for the `filter_by` parameter
            if ($request->has('filter_by') && $request->filter_by) {

                if ($request->filter_by == 'popular') {

                    $talants->orderBy('rates_avg_rate', 'desc');

                } elseif ($request->filter_by == 'latest') {

                    $talants->orderBy('created_at', 'desc');
                }
            }

            // $talants = $talants->paginate(config('constant.pagination'));
            $talants = $talants->get();

            return $this->returnData(UserTalantResource::collection($talants), [helperTrans('api.talants Data')]);

        } catch (Exception $e) {

            return $this->returnExceptionError($e);
        }
    }
    // **************************************************************
    // **************************************************************
    public function get_filter_talants(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'filter_by' => 'required|in:latest,popular',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $talants = [];
            if (! $request->filter_by) {

                $talants = UserTalant::withAvg('rates', 'rate')
                // ->orderBy('created_at', 'desc')
                    ->get();

            } elseif ($request->filter_by == 'popular') {

                $talants = UserTalant::withAvg('rates', 'rate')
                    ->orderBy('rates_avg_rate', 'desc')
                    ->get();

            } elseif ($request->filter_by == 'latest') {

                $talants = UserTalant::withAvg('rates', 'rate')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            // $talants = UserTalant::with('comments')->get();

            return $this->returnData(UserTalantResource::collection($talants), [helperTrans('api.talants Data')]);

        } catch (Exception $e) {

            return $this->returnExceptionError($e);
        }
    }

    // ************************************************************************
    // ************************************************************************
    public function add_comment_on_talant(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'talant_id' => 'required|exists:user_talants,id',
                'comment'   => 'required|max:500',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

            $comment = UserTalantComment::Create(
                [
                    'user_id'   => $user->id,
                    'talant_id' => $request->talant_id,
                    'comment'   => trim($request->comment),
                ]
            );

            // Send notification to talent owner
            $talant = UserTalant::find($request->talant_id);
            if ($talant && $talant->user_id != $user->id) {
                $notificationData = [
                    'action_id'  => $talant->id,
                    'model_name' => 'talent_comment',
                    'user_id'    => $talant->user_id,
                    'message'    => helperTrans('api.Someone commented on your talent'),
                ];
                Notification::storeNotification($notificationData);

                // Send push notification
                if ($talant->user) {
                    $firebaseService = app(FirebaseNotificationService::class);
                    $firebaseService->sendToUser(
                        $talant->user,
                        'New Comment on Your Talent',
                        'Someone commented on your talent',
                        [
                            'talent_id'  => $talant->id,
                            'comment_id' => $comment->id,
                            'type'       => 'talent_comment',
                        ]
                    );
                }
            }

            increment_User_points('add comment on talant', dynamicSetting('comment_points'));

            DB::commit();

            return $this->returnData(UserTalantCommentResource::make($comment), helperTrans("api.action add successfully"));

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }
// ***********************************************************************
// ***********************************************************************
    public function edit_talant_comment(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:user_talant_comments,id',
                'comment'    => 'required|max:500',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

            $comment = UserTalantComment::where('user_id', $user->id)
                ->find($request->comment_id);
            if (! $comment) {
                return $this->returnError(helperTrans('api.Comment not found'), 404);
            }
            $comment->update(['comment' => trim($request->comment)]);

            $message = helperTrans("api.Comment updated successfully.");

            DB::commit();

            return $this->returnData(UserTalantCommentResource::make($comment), $message);

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }
// ***********************************************************************
// ***********************************************************************
    public function delete_talant_comment($id)
    {

        try {

            $validator = Validator::make(['id' => $id], [
                'id' => 'required|exists:user_talant_comments,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

            $comment = UserTalantComment::where('user_id', $user->id)->find($id);

            if (! $comment) {
                return $this->returnError(helperTrans('api.Comment not found,or comment not related for you !'), 404);
            }

            $comment->delete();

            increment_User_points('deleted comment from talant', dynamicSetting('comment_points'), 0);

            DB::commit();

            return $this->returnSuccessMessage(helperTrans("api.Comment deleted successfully."));

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }

// ***********************************************************************
// ***********************************************************************
    public function toggle_liked_talant($id)
    {

        try {

            $validator = Validator::make(['talant_id' => $id], [
                'talant_id' => 'required|exists:user_talants,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

            $talantAction = UserTalantAction::where('user_id', $user->id)
                ->where('talant_id', $id)
                ->where('action_type', 1) //to get action like
                ->first();

            if ($talantAction) {
                $talantAction->delete();
                increment_User_points('UnLiked talant post', dynamicSetting('like_points'), 0);
                $message = 'UnLiked';
            } else {
                UserTalantAction::create([
                    'user_id'     => $user->id,
                    'talant_id'   => $id,
                    'action_type' => 1,
                    'action'      => 1,
                ]);

                // Send notification to talent owner
                $talant = UserTalant::find($id);
                if ($talant && $talant->user_id != $user->id) {
                    $notificationData = [
                        'action_id'  => $talant->id,
                        'model_name' => 'talent_like',
                        'user_id'    => $talant->user_id,
                        'message'    => helperTrans('api.Someone liked your talent'),
                    ];
                    Notification::storeNotification($notificationData);

                    // Send push notification
                    if ($talant->user) {
                        $firebaseService = app(FirebaseNotificationService::class);
                        $firebaseService->sendToUser(
                            $talant->user,
                            'New Like on Your Talent',
                            'Someone liked your talent',
                            [
                                'talent_id' => $talant->id,
                                'type'      => 'talent_like',
                            ]
                        );
                    }
                }

                increment_User_points('Liked talant post', dynamicSetting('like_points'));
                $message = 'liked';
            }

            DB::commit();

            return $this->returnSuccessMessage([helperTrans("api.$message Successfully")]);
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }

    // ************************************************
    // ************************************************
    public function increment_talant_share($id)
    {

        try {

            $validator = Validator::make(['talant_id' => $id], [
                'talant_id' => 'required|exists:user_talants,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

            $share = UserTalantAction::create([
                'user_id'     => $user->id,
                'talant_id'   => $id,
                'action_type' => 2,
                'action'      => 1,
            ]);

            DB::commit();

            $talant = UserTalant::find($id);
            // dd($share->countshares());
            return $this->returnData(['shares_count' => $talant->countshares()], [helperTrans("api.share Successfully")]);

            // Single resource instance

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }
    // ************************************************************************
    // ************************************************************************
    public function store_talant(Request $request)
    {

        try {
            $rules = [
                'video'          => 'required|mimetypes:video/mp4,video/ogg,video/webm',
                'phone'          => 'required||numeric|digits:11',
                'age'            => 'required|numeric',
                'country_id'     => 'required|exists:countries,id',
                'city_id'        => 'required|exists:cities,id',
                'address'        => 'nullable',
                'persone_heigth' => 'nullable',
                'persone_weight' => 'nullable',
            ];

            // foreach (languages() as $language) {
            //     $rules["title.$language->abbreviation"] = 'required|string|max:255';
            // }
            $rules['title.ar'] = 'required|string|max:255';

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();
            $video = '';
            if ($request->has('video')) {
                $video = $this->uploadFiles('video/talants', $request->file('video'), null);
            }

            $data            = $request->only('video', 'phone', 'age', 'country_id', 'city_id', 'address', 'persone_heigth', 'persone_weight');
            $data['user_id'] = $user->id;
            $data['video']   = $video;
            $data['title']   = $request->title;

            $talant           = UserTalant::create($data);
            $notificationData = [
                'action_id'  => $talant->id,
                'model_name' => 'talant',
                'message'    => helperTrans('api.video Uploaded Successfuly,waiting for approve'),
            ];
            Notification::storeNotification($notificationData);

            increment_User_points('Upload Talant video', dynamicSetting('upload_talant'));

            $talant->load('country', 'city');

            DB::commit();

            return $this->returnData(UserTalantResource::make($talant), [helperTrans("api.video Uploaded Successfully")]); // Single resource instance

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }

    // ************************************************************************
    // ************************************************************************
    public function update_talant(Request $request)
    {

        try {
            $rules = [
                'id'             => 'required|exists:user_talants,id',
                'video'          => 'nullable|mimetypes:video/mp4,video/ogg,video/webm',
                'phone'          => 'required||numeric|digits:11',
                'age'            => 'required|numeric',
                'country_id'     => 'required|exists:countries,id',
                'city_id'        => 'required|exists:cities,id',
                'address'        => 'nullable',
                'persone_heigth' => 'nullable',
                'persone_weight' => 'nullable',
            ];

            foreach (languages() as $language) {
                $rules["title.$language->abbreviation"] = 'required|string|max:255';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();
            $video = '';
            if ($request->has('video')) {
                $video = $this->uploadFiles('video/talants', $request->file('video'), null);
            }

            $data            = $request->only('video', 'phone', 'age', 'country_id', 'city_id', 'address', 'persone_heigth', 'persone_weight');
            $data['user_id'] = $user->id;
            $data['video']   = $video;
            $data['status']  = '1';
            $data['title']   = $request->title;

            $id     = $request->id;
            $talant = UserTalant::where('user_id', $user->id)->find($id);

            if (! $talant) {
                return $this->returnError(helperTrans('api.Talant not found,or talant not related for you !'), 404);
            }

            $talant->update($data);
            $notificationData = [
                'action_id'  => $talant->id,
                'model_name' => 'talant',
                'message'    => helperTrans('api.talant updated Successfuly,waiting for approve'),
            ];
            Notification::storeNotification($notificationData);

            increment_User_points('Upload Talant video', dynamicSetting('upload_talant'));

            $talant->load('country', 'city');

            DB::commit();

            return $this->returnData(UserTalantResource::make($talant), [helperTrans("api.video updated Successfully")]); // Single resource instance

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }
    }

    // ****************************************************************
    // ****************************************************************
    public function add_rate(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'talant_id' => 'required|exists:user_talants,id',
                'rate'      => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();

            DB::beginTransaction();

            $data            = $request->only('talant_id', 'rate');
            $data['user_id'] = $user->id;
            // $data['video']=$video;
            $talant_id = $request->talant_id;
            $talant    = TalantRate::UpdateOrCreate(
                [
                    'user_id'   => $user->id,
                    'talant_id' => $talant_id,
                ], [
                    'rate' => $request->rate,
                ]
            );

            if ($talant->wasRecentlyCreated) {

                increment_User_points('add rate to talant', dynamicSetting('add_rate'));

            }

            $talant = UserTalant::findOrfail($talant_id);

            DB::commit();

            return $this->returnData(UserTalantResource::make($talant), [helperTrans("api.talant Rate Successfully")]); // Single resource instance

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->returnExceptionError($e);
        }

    }

    // ****************************************************************
    // ****************************************************************

    public function delete_talant_video(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'talant_id' => 'required|exists:user_talants,id',
        ]);

        if ($validator->fails()) {
            return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
        }

        $user = userApi()->user();

        $talant_id = $request->talant_id;

        $talant = UserTalant::where('id', $talant_id)->first();
        // dd($talant);
        if ($talant->user_id == $user->id) {

            $this->deleteFile($talant->video);

            $talant->update(['video' => null]);

            increment_User_points('remove Talant video', dynamicSetting('upload_talant'), 0);

            return $this->returnSuccessMessage([helperTrans("api.video deleted Successfully")]);
        }

        return $this->returnError([helperTrans("api.This talant not Belong for you")]);

    }
// ***********************************************************************************
    // ****************************************************************
    // ****************************************************************
    public function user_liked_talant()
    {
        try {

            $user = userApi()->user();

            $talants = UserTalantAction::where('user_id', $user->id)->where('action_type', 1)->get();

            $talants = UserTalant::whereIn('id', $talants->pluck('talant_id'))->get();

            return $this->returnData(UserTalantResource::collection($talants), [helperTrans('api.user liked talants Data')]);

        } catch (\Exception $e) {

            return $this->returnExceptionError($e);
        }
    }
    public function get_featured_talants()
    {
        try {

            $talants = UserTalant::with(['comments' => function ($query) {
                $query->latest();
            }, 'rates'])
                ->where('status', 2)
                ->where('is_featured', 1)
                ->withAvg('rates', 'rate');

            // Check for the `filter_by` parameter

            // $talants = $talants->paginate(config('constant.pagination'));
            $talants = $talants->get();

            return $this->returnData(UserTalantResource::collection($talants), [helperTrans('api.talants Data')]);

        } catch (Exception $e) {

            return $this->returnExceptionError($e);
        }

    }

    ////////////////
    public function get_user_talant()
    {
        try {
            $user = userApi()->user();

            $talants = UserTalant::with(['comments' => function ($query) {
                $query->latest();
            }, 'rates'])
                ->where('user_id', $user->id)
                ->withAvg('rates', 'rate')
                ->get();

            return $this->returnData(UserTalantResource::collection($talants), [helperTrans('api.user talants Data')]);

        } catch (Exception $e) {
            return $this->returnExceptionError($e);
        }
    }
    public function toggle_fav_talant($id)
    {

        try {

            $validator = Validator::make(['talant_id' => $id], [
                'talant_id' => 'required|exists:user_talants,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }
            $message = 'Fav';

            $user = userApi()->user();

            DB::beginTransaction();

            $talantAction = UserTalantAction::where('user_id', $user->id)
                ->where('talant_id', $id)
                ->where('action_type', 3) //to get action like
                ->first();

            if ($talantAction) {
                $talantAction->delete();
                $message = 'UnFav';
            } else {
                UserTalantAction::create([
                    'user_id'     => $user->id,
                    'talant_id'   => $id,
                    'action_type' => 3,
                    'action'      => 1,
                ]);
            }

            DB::commit();

            return $this->returnSuccessMessage([helperTrans("api.$message Successfully")]);
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }

    public function user_favourite_talant()
    {
        try {

            $user = userApi()->user();

            $talants = UserTalantAction::where('user_id', $user->id)->where('action_type', 3)->get();

            $talants = UserTalant::whereIn('id', $talants->pluck('talant_id'))->get();

            return $this->returnData(UserTalantResource::collection($talants), [helperTrans('api.user liked talants Data')]);

        } catch (\Exception $e) {

            return $this->returnExceptionError($e);
        }
    }

}
