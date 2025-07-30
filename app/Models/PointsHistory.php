<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'type', // 'add' or 'subtract'
        'description',
        'reference_type', // e.g., 'award', 'redeem', 'activity'
        'reference_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
