<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table='customers';

    protected $fillable=['mobile', 'email', 'name', 'name', 'image', 'notification_token', 'house_no', 'building', 'area', 'street', 'city', 'state', 'pincode', 'map_address', 'lat', 'lang', 'map_json', 'reffered_by_partner', 'reffered_by'];

    protected $appends =['address'];

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

    public function partner(){
        return $this->belongsTo('App\Models\Partner', 'assigned_partner');
    }

    public function orders(){
        return $this->hasMany('App\Models\Order', 'user_id');
    }

    public function refferer(){
        return $this->belongsTo('App\Models\Customer', 'reffered_by');
    }

    public function partnerRefferer(){
        return $this->belongsTo('App\Models\Partner', 'reffered_by_partner');
    }


    public function getAddressAttribute($value){
        return ($this->house_no??'').' '.($this->building??'').' '.($this->area??'').' '.($this->street??'').' '.($this->city??'').' '.($this->state??'').' '.($this->pincode??'');
    }


    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }

}
