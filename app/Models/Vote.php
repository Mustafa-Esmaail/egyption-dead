<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

class Vote extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title','desc'];

    protected $guarded=[];

    public function choices(){

        return $this->hasMany(VoteChoice::class,'vote_id','id');
    }



    public function totalVotesCount(){

       return  VoteChoicesRate::where('vote_id',$this->id)->count();

    }

}
