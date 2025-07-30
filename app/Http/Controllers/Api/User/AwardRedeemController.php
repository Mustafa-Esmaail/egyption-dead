<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AwardRedeemRequestResource;
use App\Http\Traits\Api_Trait;
use App\Models\Award;
use App\Models\AwardRedeemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AwardRedeemController extends Controller
{
    use Api_Trait;

    public function redeem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'award_id' => 'required|exists:awards,id',
            ]);

            if ($validator->fails()) {
                return $this->returnError($validator->errors()->first());
            }

            $user = userApi()->user();
            $award = Award::findOrFail($request->award_id);

            // Check if user has enough points
            if ($user->points < $award->points) {
                return $this->returnError(helperTrans('api.you do not have enough points to redeem this award'));
            }

            // Check if user already has a pending request for this award
            $existingRequest = AwardRedeemRequest::where('user_id', $user->id)
                ->where('award_id', $award->id)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return $this->returnError(helperTrans('api.you already have a pending request for this award'));
            }

            DB::beginTransaction();

            // Create redeem request
            $redeemRequest = AwardRedeemRequest::create([
                'user_id' => $user->id,
                'award_id' => $award->id,
                'status' => 'pending',
                'user_points' => $user->points
            ]);

            DB::commit();

            return $this->returnData(new AwardRedeemRequestResource($redeemRequest->load('award')), [helperTrans('api.award redemption request submitted successfully')],201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError($e->getMessage());
        }
    }

    public function myRequests()
    {
        try {
            $user = userApi()->user();
            $requests = AwardRedeemRequest::with(['award' => function($query) {
                    $query->select('id', 'title', 'points', 'image');
                }])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();


                return $this->returnData(AwardRedeemRequestResource::collection($requests), [helperTrans('api.award redemption requests Data')]);

        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
}
