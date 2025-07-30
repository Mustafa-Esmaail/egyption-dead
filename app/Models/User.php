<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends  Authenticatable implements JWTSubject
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $guarded=[];



    protected $with=['teams'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function country(){

        return $this->belongsTo(Country::class,'country_id');
    }

    public function city(){

        return $this->belongsTo(City::class,'city_id');
    }

    public function club(){

        return $this->belongsTo(Team::class,'club_id');
    }
// *****************************************************************
// *****************************************************************

    public function academies(){

        return $this->hasManyThrough(Academy::class, UserAcademy::class, 'user_id', 'id', 'id', 'academy_id');
    }

// ********************************************************************
// ********************************************************************

    public function teams()
    {
        return $this->hasManyThrough(
            Team::class, // The final model you want to access
            UserFavouriteTeamAndPlayer::class, // The intermediate model
            'user_id', // Foreign key on UserFavouriteTeamAndPlayer table pointing to User
            'id', // Foreign key on Team table that UserFavouriteTeamAndPlayer references
            'id', // Local key on User table
            'foriegn_key' // Local key on UserFavouriteTeamAndPlayer table pointing to Team
        )->where('user_favourite_team_and_players.id_belong_to', 1);
    }
// **********************************************
// **********************************************
public function favouriteTeam(){

    return $this->teams()->first();
}

    // ************************************************************************
    // ************************************************************************
    public function player()
    {
        return $this->hasManyThrough(
            TeamPlayer::class, // The final model you want to access
            UserFavouriteTeamAndPlayer::class, // The intermediate model
            'user_id', // Foreign key on UserFavouriteTeamAndPlayer table pointing to User
            'id', // Foreign key on Team table that UserFavouriteTeamAndPlayer references
            'id', // Local key on User table
            'foriegn_key' // Local key on UserFavouriteTeamAndPlayer table pointing to Team
        )->where('user_favourite_team_and_players.id_belong_to', 2);
    }

    public function fullName(){

        return $this->first_name.' '. $this->last_name;
    }

// ****************************************************************
// ****************************************************************
    // public function routeNotificationForFcm()
    // {
    //     return $this->getDeviceTokens();
    // }



}
