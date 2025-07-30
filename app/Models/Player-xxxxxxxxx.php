<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Player extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title'];

    protected $guarded=[];

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class,'club_id','id');
    }

}
