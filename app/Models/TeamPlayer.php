<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;
class TeamPlayer extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title'];

    protected $guarded=[];

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function postion(){
        return $this->hasMany(Plan::class,'player_id');
    }



}
