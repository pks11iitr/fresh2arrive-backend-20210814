<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Partner extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'partners';

    protected $fillable =[
        'name',
        'mobile',
        'address',
        'city',
        'pincode',
        'state',
        'notification_token',
        'status',
        'support_whatsapp',
        'support_mobile'
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


    public function getAadhaarUrlAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }

    public function getPanUrlAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }


    public function getAadhaarNoAttribute($value){
        return $value??'';
    }

    public function getPanNoAttribute($value){
        return $value??'';
    }

    public function getBankAccountHolderAttribute($value){
        return $value??'';
    }

    public function getBankAccountNoAttribute($value){
        return $value??'';
    }

    public function getBankIfscAttribute($value){
        return $value??'';
    }


    public function preferedTimeSlots(){
        return $this->belongsToMany('App\Models\TimeSlot', 'prefered_slots', 'partner_id', 'slot_id');
    }


    public function areas(){
        return  $this->belongsToMany('App\Models\Area', 'area_assign', 'partner_id', 'areaid');
    }

}
