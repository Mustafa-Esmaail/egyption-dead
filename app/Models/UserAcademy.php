<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;
class UserAcademy extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }

    // ****************************************************************
    // ****************************************************************

    public function academy(){
        return $this->belongsTo(Academy::class);
    }

    public function users(){ 
        
        return $this->hasManyThrough(User::class, UserAcademy::class, 'academy_id', 'id', 'id', 'user_id');
    }

    // ***********************************************
    // ***********************************************
    public static function toggleAction($academyId, $actionType)
    {
        $user = userApi()->user(); 

        if(!$user)
            return false;
        // Retrieve or create the record
        $userAcademy = self::where('academy_id',$academyId)
                            ->where('user_id',$user->id)
                            ->where('action_type',$actionType)
                            ->first();
        
        $points = $actionType == 1 ? dynamicSetting('like_points') : dynamicSetting('subscribe_points');
        // If record exists, delete it this mean user is remove action
        if($userAcademy){

            $userAcademy->delete();

            $message = $actionType == 1 ? 'UnLiked academy' : 'UnSubscribed in academy';
            increment_User_points($message,$points,0);

            return 0;
        }

        $userAcademy = self::create([
            'user_id' => $user->id,
            'academy_id' => $academyId,
            'action_type' => $actionType,
            'action' => 1,
        ]);

        $message = $actionType == 1 ? 'Liked academy' : 'Subscribed in academy';
        increment_User_points($message,$points);

        return 1;
    }

}
