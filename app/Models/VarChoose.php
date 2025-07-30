<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Models\Area;

class VarChoose extends Model
{
    use HasFactory,HasTranslations;
    protected $guarded=[];

    public $translatable = ['choose'];


    public function vars()
    {
        return $this->belongsTo(VarTab::class,'var_id','id');
    }

// *****************************************************
// *****************************************************
    public function rates(){

        return $this->hasMany(VarChoicesRate::class,'var_choices_id','id');
    }
// *****************************************************
    public function CountTotalVars(){

        return  VarChoicesRate::where('var_id',$this->var_id)->count();

    }
// *****************************************************
    public function choicePrecent(){

        if($this->rates->count() == 0 || $this->CountTotalVars() == 0){

            return 0;   
        }     

        return $this->rates->count() / $this->CountTotalVars();
    }

// *****************************************************

    public function checkIfUserSelected(){

        $user = userApi()->user();  

        if(empty($user)){return 0;} 

        $choice = VarChoicesRate::where('user_id',$user->id)
                        ->where('var_choices_id',$this->id)
                        ->first();
        return empty($choice) ? 0 : 1;
    }




}
