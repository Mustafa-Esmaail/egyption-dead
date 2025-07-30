<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;
class Notification extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['message'];

    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function storeNotification($data){

        $user = userApi()->user();

        $userId = null;

        if(isset($data['user_id']) && $data['user_id']){

            $userId =$data['user_id'];
        }elseif(filled($user) && $user->id ){
            $userId = $user->id;
        }


        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->action_id = $data['action_id'];
        $notification->model_name = $data['model_name'];
        $notification->message = $data['message'];
        $notification->save();

        return $notification;
    }

}
