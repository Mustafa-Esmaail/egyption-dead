<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WomenFootball extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title','desc','short_desc'];

    protected $guarded=[];

    public function women_category()
    {
        return $this->belongsTo(WomenFootballCategory::class, 'women_football_category_id', 'id');
    }

    // *****************************************************
    // *****************************************************
    public function actions(){
        return $this->hasMany(WomenFootballAction::class,'women_football_id','id');
    }
    // ******************************************************
    // ******************************************************
    public function countLikes()
    {
        return $this->actions()
        ->where('action_type', 1)
        ->where('action', 1)
        ->count(); 
    }
    // *****************************************
    public function check_auth_action($actionType){

        $user = userApi()->user();  
       
        $action = WomenFootballAction::where('women_football_id', $this->id)
                ->where('user_id', $user->id)
                ->where('action_type', $actionType)
                ->where('action', 1)
                ->first();

        return empty($action) ? 0 : 1;
    }


    
}
