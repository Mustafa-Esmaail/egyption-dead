<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointsHistoryController extends Controller
{
    public function myPoints(Request $request)
    {
        try {
            $user = userApi()->user();

            $pointsHistory = UserPoint::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($point) {
                    return [
                        'id' => $point->id,
                        'type' => $point->type == UserPoint::TYPE_INCREMENT ? 'increment' : 'decrement',
                        'points' => $point->points,
                        'action' => $point->action,
                        'date' => $point->created_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Points history retrieved successfully',
                'data' => [
                    'current_points' => $user->points,
                    'history' => $pointsHistory
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve points history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
