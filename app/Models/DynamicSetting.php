<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;

class DynamicSetting extends Model
{
    use HasFactory;

    // public $translatable = ['title','desc'];

    protected $guarded=[];

    // public function player(){
        
    //     return $this->belongsTo(TeamPlayer::class,'player_id');
    // }

}
