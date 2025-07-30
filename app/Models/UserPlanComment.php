<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;

class UserPlanComment extends Model
{
    use HasFactory;

   

    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    
    public function userPlan(){
        return $this->belongsTo(UserPlan::class);
    }


}
