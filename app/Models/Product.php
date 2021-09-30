<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Inventory;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, Active;

    protected $table = 'products';

    protected $fillable=['name', 'company', 'image', 'display_pack_size', 'price_per_unit', 'cut_price_per_unit', 'unit_name', 'packet_price', 'consumed_quantity', 'isactive', 'tag', 'min_qty', 'max_qty', 'commissions'];

    protected $appends = ['percent', 'commissions'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }

    public function getCommissionsAttribute($value){
        return floor($value*$this->packet_price/100);
    }

    public function category(){
        return $this->belongsToMany('App\Models\Category', 'product_category', 'product_id', 'category_id');
    }


    public function getPercentAttribute($value){

        if(!empty($this->getRawOriginal('price_per_unit')) && empty($this->getRawOriginal('cut_price_per_unit')) && $this->getRawOriginal('price_per_unit') < $this->getRawOriginal('cut_price_per_unit')){
            return number_format(($this->getRawOriginal('cut_price_per_unit')-$this->getRawOriginal('price_per_unit'))*100/$this->getRawOriginal('cut_price_per_unit'), 1);
        }

        return 0;

    }

    public function getCutPricePerUnitAttribute($value){
        return $value??0;
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

    public static function getStock($product_id){

        //check for out of stock
        $purchased_stock= Inventory::purchased_quantity([$product_id]);
        $consumed_stock=OrderDetail::consumed_quantity([$product_id]);

        $stock=($purchased_stock[$product_id]??0) - ($consumed_stock[$product_id]??0);

        return $stock;

    }




}
