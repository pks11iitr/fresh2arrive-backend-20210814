<?php

namespace App\Models;

use App\Models\Traits\Active;
use Google\Service\OSConfig\Inventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, Active;

    protected $table = 'products';

    protected $fillable=['name', 'company', 'image', 'display_pack_size', 'price_per_unit', 'cut_price_per_unit', 'unit_name', 'packet_price', 'consumed_quantity', 'isactive', 'tag'];

    protected $appends = ['percent'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }


    public function getPercentAttribute($value){

        if(!empty($this->getRawOriginal('price_per_unit')) && empty($this->getRawOriginal('cut_price_per_unit')) && $this->getRawOriginal('price_per_unit') < $this->getRawOriginal('cut_price_per_unit')){
            return number_format(($this->getRawOriginal('cut_price_per_unit')-$this->getRawOriginal('price_per_unit'))*100/$this->getRawOriginal('cut_price_per_unit'), 1);
        }

        return 0;

    }

    public static function setCartQuantity(&$products, $cart){
        foreach($products as $p){
            $p->cart_quantity=$cart[$p->id]??0;
        }
    }

    public static function setLimitedStock(&$products){
        $pids=[];
        foreach($products as $p){
            $pids[]=$p->id;
        }

        // calculate total stock
        $purchased_quantity=Inventory::purchased_quantity($pids);

        // calculate sold out stock
        //$sold_quantity=Order
        //get difference & set flag


    }




}
