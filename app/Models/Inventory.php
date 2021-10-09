<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    use HasFactory, Active;

    protected $table='inventory';



    public static function purchased_quantity($pids){
        $purchased_items=Inventory::whereIn('product_id', $pids)
            ->select(DB::raw('sum(quantity) as quantity'), 'product_id')
            ->groupBy('product_id')
            ->get();
        $purchased_quantity=[];
        foreach($purchased_items as $p){
            $purchased_quantity[$p->product_id]=$p->quantity;
        }

        return $purchased_quantity;

    }
}
