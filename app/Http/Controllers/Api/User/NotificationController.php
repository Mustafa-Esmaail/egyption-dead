<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PointsResource;

use App\Models\Notification;
use App\Models\UserPoint;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\Api_Trait;
use Carbon\Carbon;
use App\Notifications\PushNotification;
use App\Http\Controllers\HelperClasses\NotificationHelper;


class NotificationController extends Controller
{
    use Api_Trait;

    //
    public function get_notification()
    {
        try{

            $user = userApi()->user();

            if(!$user){

                return $this->returnError(helperTrans('api.user not found'));
            }

            $notifications = Notification::where('user_id',$user->id)->paginate(15);

            $data['notifications'] = NotificationResource::collection($notifications);

            $data['pagination'] = $this->paginationData($notifications);

            return $this->returnData($data,[helperTrans('api.notification data Data')]);

        }catch(Exception $e){
            return $this->ExceptionError($e);
        }
    }

    // *********************************************************************
    // *********************************************************************
    public function get_user_points_transction()
    {
        try{

            $user = userApi()->user();

            if(!$user){

                return $this->returnError(helperTrans('api.user not found'));
            }

            // $transaction = UserPoint::where('user_id',$user->id)->orderBy('id','desc')->paginate(15);

            // $data['transaction'] = PointsResource::collection($transaction);


            $transaction = UserPoint::selectRaw('DATE(created_at) as created_date, COUNT(*) as transaction_count')
            ->where('user_id', $user->id)
            ->groupBy('created_date')
            ->orderBy('created_date', 'desc')
            ->paginate(15);


            $data['transaction'] = $transaction->map(function ($group) use ($user) {

                $date = Carbon::parse($group->created_date);

                // Set locale for Arabic
                $date->locale(session_lang());

                // Format to day number and month name
                $formattedDate = $date->translatedFormat('j F');

                $details = UserPoint::where('user_id', $user->id)
                ->whereDate('created_at', $group->created_date)
                ->get();

            return [
                'date' => $formattedDate,
                'transaction_count' => $group->transaction_count,
                'details' => PointsResource::collection($details),
            ];
            });
            $data['total_points'] = $user->points;

            $data['pagination'] = $this->paginationData($transaction);

            return $this->returnData($data,[helperTrans('api.points transaction Data')]);

        }catch(Exception $e){

        }
    }
    // *****************************************************************
    // *****************************************************************
    public function get_firebase_notification(){

        try{

            // dd(phpinfo());
            $user = userApi()->user();

            $notifyy = $user->notify(new PushNotification('111','2222',['aaaaaa','ppppppppp']));

            return $this->returnSuccessMessage([helperTrans('api.notification sent success !!')]);

        }catch(Exception $e){

            return $this->ExceptionError($e);
        }
    }

}
