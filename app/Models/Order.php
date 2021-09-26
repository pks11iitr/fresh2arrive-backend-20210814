<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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
        'delivery_partner'
    ];

    protected $appends = ['placed_on', 'delivery_schedule'];

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
}
