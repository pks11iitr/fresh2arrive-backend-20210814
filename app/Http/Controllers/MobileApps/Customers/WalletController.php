<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Events\RechargeConfirmed;
use App\Models\Wallet;
use App\Services\Payment\RazorPayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function __construct(RazorPayService $pay){
        $this->pay=$pay;
    }
    public function index(Request $request)
    {
        $user = $request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $wallet_transactions=[];
        if ($user) {
            //die;
            $wallet_transactions = Wallet::where('user_id', $user->id)
                ->where('iscomplete', true)
                ->whereIn('amount_type', ['CASH','POINT'])
                ->select('amount', 'created_at', 'description', 'type', 'balance', 'created_at')
                ->orderBy('id', 'desc')
                ->paginate(15);
            //return $wallet_transactions;

            //return $historyobj;
//            foreach($historyobj as $h){
//                $wallet_transactions[] =[
//                    'date'=>date('d M Y', strtotime($h->created_at)),
//                    'time'=>date('h:iA', strtotime($h->created_at)),
//                    'amount'=>$h->amount,
//                    'balance'=>$h->balance,
//                    'description'=>$h->description,
//                    'type'=>$h->type
//                ];
//            }


            $balance = Wallet::balance($user->id);

        } else {
            $wallet_transactions = [];
            $balance = 0;
        }

        return [
            'status' => 'success',
            'action' => '',
            'display_message'=>'',
            'data' => compact('wallet_transactions', 'balance')
        ];
    }


    public function userbalance(Request $request){

        $user = $request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $balance = Wallet::balance($user->id);
        //$cashbackpoints = Wallet::points($user->id);
            return [
                'status' => 'success',
                'message' => 'success',
                'data'=>compact('balance')
        ];

    }

    public function addMoney(Request $request){
        $user = $request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $request->validate([
            'amount'=>'required|integer|min:1'
        ]);


            //delete all incomplete attempts
//        Wallet::where('user_id', $user->id)
//            ->where('iscomplete', false)
//            ->delete();

        //start new attempt
        $wallet=Wallet::create([
            'refid'=>env('MACHINE_ID').time(),
            'type'=>'Credit',
            'amount_type'=>'CASH',
            'amount'=>$request->amount,
            'description'=>'Wallet Recharge',
            'user_id'=>$user->id
        ]);

        $response=$this->pay->generateorderid([
            "amount"=>$wallet->amount*100,
            "currency"=>"INR",
            "receipt"=>$wallet->refid.'',
        ]);
        $responsearr=json_decode($response);
        if(isset($responsearr->id)){
            $wallet->order_id=$responsearr->id;
            $wallet->order_id_response=$response;
            $wallet->save();
            return [
                'status'=>'success',
                'action'=>'',
                'display_message'=>'',
                'data'=>[
                    'id'=>$wallet->id,
                    'order_id'=>$wallet->order_id,
                    'amount'=>$wallet->amount*100,
                    'mobile'=>$user->mobile
                ]
            ];
        }else{
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Please cannot be initiated',
                'data'=>[]
            ];
        }

    }

    public function verifyRecharge(Request $request){

        $user = $request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $wallet=Wallet::where('order_id', $request->razorpay_order_id)->first();
        if(!$wallet){
            $user = $request->user;
            if(!$user)
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Invalid Request',
                    'data'=>[]
                ];
        }
        $paymentresult=$this->pay->verifypayment($request->all());
        if($paymentresult){
            $wallet->payment_id=$request->razorpay_payment_id;
            $wallet->payment_id_response=$request->razorpay_signature;
            $wallet->iscomplete=true;
            $wallet->save();

            event(new RechargeConfirmed($wallet));

            return [
                'status'=>'success',
                'action'=>'',
                'display_message'=>'Payment is successful',
                'data'=>[]
            ];
        }else{
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Payment unsuccessful',
                'data'=>[]
            ];
        }
    }


}
