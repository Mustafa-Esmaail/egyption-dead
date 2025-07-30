<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;

class UserTalantComment extends Model
{
    use HasFactory;

   

    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    
    public function userTalant(){
        return $this->belongsTo(UserTalant::class);
    }


}
