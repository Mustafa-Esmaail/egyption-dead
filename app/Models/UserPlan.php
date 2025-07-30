<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

class UserPlan extends Model
{
    use HasFactory;

    // public $translatable = ['title'];

    protected $guarded=[];
    protected $table='user_plans';

    public function plans(){
        return $this->hasMany(Plan::class,'plan_id');
    }  
    // ****************************************************
    
    public function type(){

       $typeMapping = [
            1 => 'basic',
            2 => 'reserve',
        ];
    
        return $typeMapping[$this->type] ?? null; 
    }
    // ****************************************************
    public function user(){
    
            return $this->belongsTo(User::class,'user_id');
    }
    // *******************************************************
    public function actions(){
        return $this->hasMany(UserPlanAction::class,'user_plan_id','id');
    }
    // *******************************************************
    public function comments(){

        return $this->hasMany(UserPlanComment::class,'user_plan_id','id');
    }
    
    // **************************************************************
    // **************************************************************
    public function countComments(){

        return $this->comments()->count();
    }
    // **************************************************************
    // **************************************************************
    public function countLikes()
    {
        return $this->actions()->where('action_type', 1)->count(); 
    }
    
    // **************************************************************
    // **************************************************************
    public function countshares()
    {
        return $this->actions()->where('action_type', 2)->count(); 
    }

    // **************************************************************
    // **************************************************************
    public function rates()
    {
        return $this->hasMany(TalantRate::class,'talant_id','id');
    }
    
    
    // **************************************************************
    // **************************************************************
    public function checkIfAuthUserLiked()
    {   
        $user = userApi()->user(); 

        $action = $this->actions()
            ->where('user_id', $user->id)
            // ->where('user_plan_id', $user->id)
            ->where('action_type', 1)
            ->where('action', 1)
            ->first();

        return empty($action) ? 0 : 1;
    }
    // *******************************************************
    public function postion(){
        return $this->hasMany(Plan::class,'plan_id');
    }
    // *******************************************************

    
}
