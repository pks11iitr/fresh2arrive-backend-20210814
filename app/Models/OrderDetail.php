<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'image',
        'price',
        'cut_price',
        'packet_price',
        'quantity',
        'packet_count',
        'status'
    ];

    public static function consumed_quantity($pids){
        $consumed_items=OrderDetail::whereIn('product_id', $pids)
            ->select(DB::raw('sum(quantity*packet_count) as quantity'), 'product_id')
            ->groupBy('product_id')
            ->get();
        $consumed_quantity=[];
        foreach($consumed_items as $p){
            $consumed_quantity[$p->product_id]=$p->quantity;
        }

        return $consumed_quantity;

    }

}
