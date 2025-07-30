<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;
use App\Models\User;

class UserCoach extends Model
{
    use HasFactory;

    protected $guarded=[];

    // public $translatable = ['title'];

    // public function user()
    // {
    //     return $this->belongsTo(Country::class,'user_id','id');
    // }
}
