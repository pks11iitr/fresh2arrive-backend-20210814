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
        'echo_charges'
    ];


    public function details(){
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }
}
