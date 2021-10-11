<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory, Active;


    protected $table='banners';

    protected $fillable=['type', 'image', 'isactive','sequence_no'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }


    public function products(){
       return $this->belongsToMany('App\Models\Product','banner_products', 'banner_id' , 'product_id');
    }
}
