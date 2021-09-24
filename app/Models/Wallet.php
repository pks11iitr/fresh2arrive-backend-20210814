<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Wallet extends Model
{
    protected $table='wallet';

    protected $fillable=[
        'refid',
        'type', //Credit, Debit
        'amount',
        'balance',
        'description',
        'iscomplete',
        'order_id',
        'order_id_response',
        'payment_id',
        'payment_id_response',
        'user_id',
        'amount_type'];

    protected $hidden=[ 'updated_at', 'deleted_at','iscomplete', 'order_id_response', 'payment_id', 'payment_id_response', 'order_id', 'balance'];

    protected $appends=['date', 'time'];

    public static function balance($userid){
        $wallet=Wallet::where('user_id', $userid)
            ->where('iscomplete', true)
            ->select(DB::raw('sum(amount) as total'), 'type')
            ->groupBy('type')->get();
        $balances=[];
        foreach($wallet as $w){
            $balances[$w->type]=$w->total;
        }

        return round(($balances['Credit']??0)-($balances['Debit']??0),2);
    }



    public static function updatewallet($userid, $description, $type, $amount, $amount_type, $orderid=null){
        $balance = Wallet::balance($userid);
        if($type=='Credit')
        {
            $balance = $balance + $amount;
        }else{
            $balance = $balance - $amount;
        }
        Wallet::create(['user_id'=>$userid, 'description'=>$description, 'type'=>$type, 'iscomplete'=>1, 'amount'=>$amount, 'amount_type'=>$amount_type, 'order_id'=>$orderid, 'refid'=>date('YmdHis'), 'balance'=>$balance]);
    }


    // deduct amount from wallet if applicable
    public static function payUsingWallet($order){
        $walletbalance=Wallet::balance($order->user_id);
        $fromwallet=($order->total>=$walletbalance)?$walletbalance:$order->total;
        $order->usingwallet=true;
        $order->fromwallet=$fromwallet;
        if($order->total-$fromwallet>0){
            $paymentdone='no';
        }else{
            Wallet::updatewallet($order->user_id,'Paid for Order ID:'.$order->refid, 'Debit',$fromwallet, 'CASH');
            $order->payment_status='paid';
            $paymentdone='yes';
        }
        $order->save();
        return [
            'paymentdone'=>$paymentdone,
            'fromwallet'=>$fromwallet
        ];
    }

//    public function getIconAttribute($value){
//        if($this->type=='Debit')
//            return Storage::url('images/red.png');
//        else
//            return Storage::url('images/green.png');
//
//    }

    public function getDateAttribute($value){
        return date('d M y', strtotime($this->created_at));
    }

    public function getTimeAttribute($value){
        return date('h:i A', strtotime($this->created_at));
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

}
