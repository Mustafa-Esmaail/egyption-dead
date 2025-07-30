<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;

class UserTalantAction extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function talant(){
        return $this->belongsTo(UserTalant::class);
    }


    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
    
    // **************************************************************
    // **************************************************************

    public function city()
    {
        return $this->belongsTo(City::class,'city_id','id');
    }
    
    // **************************************************************
    // **************************************************************

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    } 

    // **************************************************************
    // **************************************************************
    public function club()
    {
        return $this->belongsTo(Club::class,'club_id','id');
    }

}
