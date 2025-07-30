<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Predict extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title','desc'];

    protected $guarded=[];
    protected $table='predicts';

}
