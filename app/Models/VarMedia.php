<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VarMedia extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['desc'];

    protected $guarded=[];
    protected $table='vars_media';

}
