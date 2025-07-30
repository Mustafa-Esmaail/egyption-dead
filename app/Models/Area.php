<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Area extends Model
{
    use HasFactory;

    use HasFactory,HasTranslations;

    public $translatable = ['name'];

    // public $translatable = [''];
    protected $table="areas";

    protected $guarded=[];

    public function country(){

        return $this->belongsTo(Country::class,'country_id');
    }

    public function city(){

        return $this->belongsTo(City::class,'city_id');
    }
}
