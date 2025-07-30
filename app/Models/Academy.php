<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;
class Academy extends Model
{
    use HasFactory,HasTranslations;

    public $translatable = ['title','desc'];

    protected $guarded=[];

    public function userAcademies()
    {
        return $this->hasMany(UserAcademy::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function createdSince(){

        return formatTimeDifference($this->created_at);
    }

    public function countSubscribers()
    {
        return $this->userAcademies()
                    ->where('action_type', 2)
                    ->where('action', 1)
                    ->count(); // Count only subscribed users
    }

    public function countLikes()
    {
        return $this->userAcademies()
        ->where('action_type', 1)
        ->where('action', 1)
        ->count(); // Count only subscribed users
    }

    public function check_auth_action($actionType){

        $user = userApi()->user();

        if(empty($user)){return 0;}

        $userAcademy = UserAcademy::where('user_id',$user->id)
            ->where('academy_id', $this->id)
            ->where('action',1)
            ->where('action_type',$actionType) // 1 = like 2 = subscrib
            ->first();

        return empty($userAcademy) ? 0 : 1;
    }

    public function scopeFilter($query, $filters)
    {
        return $query->when($filters['city_id'] ?? false, function ($query, $cityId) {
            return $query->where('city_id', $cityId);
        })->when($filters['country_id'] ?? false, function ($query, $countryId) {
            return $query->where('country_id', $countryId);
        });
    }

}
