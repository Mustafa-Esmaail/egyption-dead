<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\AwardRedeemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AwardRedeemController extends Controller
{
    public function redeem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'award_id' => 'required|exists:awards,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = userApi()->user();
            $award = Award::findOrFail($request->award_id);

            // Check if user has enough points
            if ($user->points < $award->points) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have enough points to redeem this award'
                ], 400);
            }

            // Check if user already has a pending request for this award
            $existingRequest = AwardRedeemRequest::where('user_id', $user->id)
                ->where('award_id', $award->id)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already have a pending request for this award'
                ], 400);
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

            return response()->json([
                'status' => true,
                'message' => 'Award redemption request submitted successfully',
                'data' => $redeemRequest
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your request'
            ], 500);
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

            return response()->json([
                'status' => true,
                'data' => $requests
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching your requests' . $e->getMessage()
            ], 500);
        }
    }
}
