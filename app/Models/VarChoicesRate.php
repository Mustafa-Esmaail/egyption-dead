<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;

class VarChoicesRate extends Model
{
    use HasFactory;

    // public $translatable = ['action'];

    protected $guarded=[];

    protected $table='var_choices_rates';

}
