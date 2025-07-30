<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;
class WomenFootballAction extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user(){
        
        return $this->belongsTo(User::class);
    }

    // ****************************************************************
    // ****************************************************************

    public function womenFootball(){

        return $this->belongsTo(WomenFootball::class);
    }

    public function users(){ 
        return $this->belongsTo(User::class);
    }
// ************************************************************************
// ************************************************************************

// public static function toggleAction($womenFootballId, $actionType)
// {
//     $user = userApi()->user(); 

//     if(!$user)
//         return false;
//     // Retrieve or create the record
//     $womenFootball = self::firstOrNew(
//         [
//             'user_id' => $user->id,
//             'women_football_id' => $womenFootballId,
//             'action_type' => $actionType,
//         ]
//     );

//     // Toggle the action value
//     $womenFootball->action = $womenFootball->exists ? !$womenFootball->action : 1;
//     $womenFootball->save();
//     // Return message based on action value
//     return $womenFootball->action;
// }

public static function toggleAction($womenFootballId, $actionType)
{
    $user = userApi()->user(); 

    if(!$user)
        return false;
    // Retrieve or create the record
    $womenPost = self::where('women_football_id',$womenFootballId)
                        ->where('user_id',$user->id)
                        ->where('action_type',$actionType)
                        ->first();
                        
  
    $points = $actionType == 1 ? dynamicSetting('like_points') : dynamicSetting('subscribe_points');
    // If record exists, delete it this mean user is remove action
    if($womenPost){

        $womenPost->delete();

        $message = $actionType == 1 ? 'UnLiked WomenFootball post' : 'UnSubscribed in WomentFootball';
        increment_User_points($message,$points,0);

        return 0;
    }

    $womenPost = self::create([
        'user_id' => $user->id,
        'women_football_id' => $womenFootballId,
        'action_type' => $actionType,
        'action' => 1,
    ]);

    $message = $actionType == 1 ? 'Liked WomenFootball Post' : 'Subscribed WomenFootball';

    increment_User_points($message,$points);

    return 1;
}













}
