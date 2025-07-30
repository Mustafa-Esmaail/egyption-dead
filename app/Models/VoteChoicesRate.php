<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VoteChoicesRate extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['action'];

    protected $guarded=[];

    protected $table='vote_choices_rates';

}
