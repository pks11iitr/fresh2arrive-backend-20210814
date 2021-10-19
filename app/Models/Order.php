<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory, DocumentUploadTrait;

    protected $table ='orders';

    protected $fillable =[
        'refid',
        'order_total',
        'user_id',
        'coupon_applied',
        'coupon_discount',
        'delivery_date',
        'delivery_slot',
        'delivery_time',
        'echo_charges',
        'status',
        'delivery_partner',
        'is_completed',
        'delivered_at',
        'is_accepted',
        'is_reviewed',
        'on_time',
        'on_doorstep',
        'review',
        'rating'
    ];

    protected $appends = ['placed_on', 'delivery_schedule', 'order_date', 'delivery_at'];

    public function getPlacedOnAttribute($value){
        return date('D, d M Y, h:i A', strtotime($this->created_at));
    }


    public function getDeliveryScheduleAttribute($value){
        return date('D, d M Y, ', strtotime($this->delivery_date)).$this->delivery_time;
    }


    public function details(){
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }


    public function partner(){
        return $this->belongsTo('App\Models\Partner', 'delivery_partner');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function getOrderDateAttribute($value){
        return date('D, d M Y, ', strtotime($this->created_at));
    }

    public function getDeliveryAtAttribute($value){
        return date('D, d M Y, ', strtotime($this->delivery_date));
    }

    public function getDeliveryImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }


}
