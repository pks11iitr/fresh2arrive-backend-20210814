<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'display_pack_size',
        'company',
        'image',
        'price',
        'cut_price',
        'unit_name',
        'packet_price',
        'quantity',
        'packet_count',
        'status'
    ];


    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }

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

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

}
