<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VarTab extends Model
{
    use HasFactory,HasTranslations;

    protected $table='vars';
    protected $guarded=[];

    public $translatable = ['desc'];

    public function media()
    {
        return $this->hasMany(VarMedia::class, 'var_id', 'id');
                       
    }   
    
    public function choices()
    {
        return $this->hasMany(VarChoose::class, 'var_id', 'id');
                       
    }   
    public function images()
    {
        return $this->hasMany(VarMedia::class, 'var_id', 'id')->where('type', 1);
                       
    }   
    
    public function videos(){

        return $this->hasMany(VarMedia::class,'var_id','id')->where('type',2);
    }

}
