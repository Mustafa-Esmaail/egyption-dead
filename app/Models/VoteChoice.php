<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VoteChoice extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title'];

    protected $guarded=[];

    public function vote()
    {
        return $this->belongsTo(Vote::class, 'vote_id', 'id');
    }

    // *****************************************************
    // *****************************************************
    public function rates(){

        return $this->hasMany(VoteChoicesRate::class,'vote_choices_id','id');
    }

    public function CountTotalVotes(){

        return  VoteChoicesRate::where('vote_id',$this->vote_id)->count();

    }

    public function choicePrecent(){

        if($this->rates->count() == 0 || $this->CountTotalVotes() == 0){

            return 0;   
        }     

        return $this->rates->count() / $this->CountTotalVotes();
    }

    // *****************************************************

    public function checkIfUserSelected(){

        $user = userApi()->user();  

        if(empty($user)){return 0;} 

        $choice = VoteChoicesRate::where('user_id',$user->id)
                        ->where('vote_choices_id',$this->id)
                        ->first();
        return empty($choice) ? 0 : 1;
    }



    
}
