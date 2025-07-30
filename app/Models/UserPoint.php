<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;

class UserPoint extends Model
{
    use HasFactory;

    // public $translatable = ['title'];

    protected $table = 'user_points';

    protected $fillable = [
        'type',
        'user_id',
        'points',
        'action'
    ];

    // Constants for type
    const TYPE_DECREMENT = '0';
    const TYPE_INCREMENT = '1';

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
