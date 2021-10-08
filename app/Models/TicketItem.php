<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketItem extends Model
{
    use HasFactory;

    protected $table ='ticket_items';

    protected $fillable =['ticket_id', 'detail_id', 'packet_count', 'issue', 'image'];

    protected $appends =['ticket_raised_for'];


    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);

        return '';
    }


    public function details(){
        return $this->belongsTo('App\Models\OrderDetail', 'detail_id');
    }

    public function getTicketRaisedForAttribute(){
        return 'Issue Raised for '.$this->packet_count. ' packs';
    }


}
