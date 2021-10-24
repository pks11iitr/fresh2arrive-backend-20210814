<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    use Active;

    protected $table='area_list';
    protected $appends =['address'];
    protected $fillable=['name', 'isactive', 'city','state','pincode'];
}
