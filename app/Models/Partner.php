<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Partner extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'partners';

    public $fillable =[
        'name',
        'mobile',
        'status',
        'notification_token',
        'address',
        'city',
        'pincode',
        'state'
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function reportedUsers(){
        return $this->belongsToMany('App\Models\Customer', 'reported_users', 'reported_by', 'reported_user');
    }
}
