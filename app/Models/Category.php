<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory, Active;

    protected $table = 'categories';


    protected $fillable=['name', 'image', 'isactive', 'earn_upto', 'font_color'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }

}
