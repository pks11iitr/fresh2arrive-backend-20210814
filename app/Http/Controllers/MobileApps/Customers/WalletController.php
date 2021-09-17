<?php

namespace App\Http\Controllers\MobileApps\Api;

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
            $historyobj = Wallet::where('user_id', $user->id)
                ->where('iscomplete', true)
                ->whereIn('amount_type', ['CASH','POINT'])
                ->select('amount', 'created_at', 'description', 'refid', 'type','amount_type', 'balance')
                ->orderBy('id', 'desc')
                ->paginate(20);

            foreach($historyobj as $h){
                $wallet_transactions[] =[
                    'date'=>date('d M Y', strtotime($h->created_at)),
                    'time'=>date('h:iA', strtotime($h->created_at)),
                    'amount'=>$h->amount,
                    'balance'=>$h->balance,
                    'description'=>$h->description,
                    'type'=>$h->type
                ];
            }


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


    public function userbalance(){
        $user = auth()->guard('customerapi')->user();
        if (!$user)
            return [
                'status' => 'failed',
                'message' => 'Please login to continue'
            ];
        $balance = Wallet::balance($user->id);
        $cashbackpoints = Wallet::points($user->id);
        if($balance || $cashbackpoints){
            return [
                'status' => 'success',
                'message' => 'success',
                'data'=>compact('cashbackpoints','balance')
            ];
        }else{
            return [
                'status' => 'failed',
                'message' => 'some error Found'
            ];
        }
    }

    public function addMoney(Request $request){
        $request->validate([
            'amount'=>'required|integer|min:1'
        ]);

        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];
        if($user){
            //delete all incomplete attempts
            Wallet::where('user_id', $user->id)->where('iscomplete', false)->delete();

            //start new attempt
            $wallet=Wallet::create(['refid'=>env('MACHINE_ID').time(), 'type'=>'Credit', 'amount_type'=>'CASH', 'amount'=>$request->amount, 'description'=>'Wallet Recharge','user_id'=>$user->id]);

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
                    'data'=>[
                        'id'=>$wallet->id,
                        'order_id'=>$wallet->order_id,
                        'amount'=>$wallet->amount*100
                    ]
                ];
            }else{
                return response()->json([
                    'status'=>'failed',
                    'message'=>'Payment cannot be initiated',
                    'data'=>[
                    ],
                ], 200);
            }
        }

        return response()->json([
            'status'=>'failed',
            'message'=>'logout',
            'data'=>[
            ],
        ], 200);

    }

    public function verifyRecharge(Request $request){
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];
        $wallet=Wallet::where('order_id', $request->razorpay_order_id)->first();
        if(!$wallet){
            return [
                'status'=>'failed',
                'message'=>'No Record found'
            ];
        }
        $paymentresult=$this->pay->verifypayment($request->all());
        if($paymentresult){
            $wallet->payment_id=$request->razorpay_payment_id;
            $wallet->payment_id_response=$request->razorpay_signature;
            $wallet->iscomplete=true;
            $wallet->save();

            event(new RechargeConfirmed($wallet));

            return response()->json([
                'status'=>'success',
                'message'=>'Payment is successfull',
                'errors'=>[

                ],
            ], 200);
        }else{
            return response()->json([
                'status'=>'failed',
                'message'=>'Payment is not successfull',
                'errors'=>[

                ],
            ], 200);
        }
    }


}
