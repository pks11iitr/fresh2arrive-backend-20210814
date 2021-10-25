<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
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
        'support_mobile',
        'occupation',
        'referred_by',
        'store_name',
        'house_no',
        'landmark',
        'map_address'
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

   // protected $appends =['address'];

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

    public function orders(){
        return $this->hasMany('App\Models\Order', 'delivery_partner');
    }

    public function clients(){
        return $this->hasMany('App\Models\Customer', 'assigned_partner');
    }


    public function preferedTimeSlots(){
        return $this->belongsToMany('App\Models\TimeSlot', 'prefered_slots', 'partner_id', 'slot_id');
    }


    public function areas(){
        return  $this->belongsToMany('App\Models\Area', 'area_assign', 'partner_id', 'areaid');
    }

    public static function getAvailablePartner($location, $ignore_list=[] ){

        $area = $location;
        $partners = Partner::whereHas('areas', function($areas) use($area){

            $areas->where('area_list.name', $area);

        })
            ->where('status', 1)
            ->withCount('clients')
            ->whereNotIn('partners.id', $ignore_list)
            ->get();

        $partners_count =[];
        foreach($partners as $p){
            $partners_count[$p->id]=$p->clients_count;
        }

        asort($partners_count);

//        $pids = $partners->map(function($element){
//            return $element->id;
//        })->toArray();

//        $partners_count = Order::where('status', '!=', 'pending')
//            ->select(DB::raw('count(*) as count'), 'delivery_partner')
//            ->groupBy('delivery_partner')
//            ->whereIn('delivery_partner', $pids)
//            ->orderBy('count', 'asc')
//            ->get();

//        $partners_data=[];
//        foreach($pids as $p){}

        if(count($partners_count)){
            $keys=array_keys($partners_count);
            return $keys[0];
        }


        //no partner found
        return 0;



    }

}
