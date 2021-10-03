<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table ='tickets';

    protected $fillable =['refid', 'user_id', 'partner_id', 'status', 'order_id', 'ticket_type', 'customer_comments', 'partner_comments', 'partner_approved', 'admin_comments'];


    public function items(){
        return $this->hasMany('App\Models\TicketItem', 'ticket_id');
    }


    public function getCustomerCommentsAttribute($value){
        return $value??'';
    }

    public function getPartnerCommentsAttribute($value){
        return $value??'';
    }

    public function getAdminCommentsAttribute($value){
        return $value??'';
    }
}
