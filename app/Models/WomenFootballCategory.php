<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WomenFootballCategory extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title'];

    protected $guarded=[];
    protected $table='women_football_categories';

}
