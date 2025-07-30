<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Models\Area;

class City extends Model
{
    use HasFactory,HasTranslations;
    protected $guarded=[];

    public $translatable = ['title'];

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
