<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request){
        $user=$request->user;

        $inactive_users = Customer::whereDoesntHave('orders', function($orders){
            $orders->whereIn('status', ['processing', 'confirmed', 'processing', 'dispatched', 'delivered']);
        })
                ->where('assigned_partner', $user->id)
                ->count();

        $reported = $user->reportedUsers()->count();

        $active_users = Order::join('customers', 'customers.id', '=', 'orders.user_id')
            ->where('delivery_partner', $user->id)
            ->whereIn('orders.status', ['confirmed', 'processing', 'dispatched', 'delivered'])
            ->groupBy('user_id')
            ->orderBy('delivery_date', 'desc')
            ->select(DB::raw('max(orders.delivery_date) as delivery_date'), DB::raw("count(*) as count"), 'user_id')
            ->get();

        $user_categories_count=[
            'all'           =>  0,
            'platinum'      =>  0,
            'gold'          =>  0,
            'silver'        =>  0,
            'bronze'        =>  0,
            'slow_moving'   =>  0,
            'inactive'       =>  $inactive_users,
            'reported'      =>  $reported
        ];

        $last_order_date=[];
        $order_count=[];
        foreach($active_users as $au){
            $last_order_date[$au->user_id]=$au->delivery_date;
            $order_count[$au->user_id]=$au->count;
            $user_categories_count['all']++;
            if($au->count == 1)
                $user_categories_count['slow_moving']++;
            if($au->delivery_date >= date('Y-m-d', strtotime('-7 days'))){
                $user_categories_count['platinum']++;
            }else if($au->delivery_date >= date('Y-m-d', strtotime('-14 days')) && $au->delivery_date <= date('Y-m-d', strtotime('-8 days')))
                $user_categories_count['gold']++;
            else if($au->delivery_date >= date('Y-m-d', strtotime('-21 days')) && $au->delivery_date <= date('Y-m-d', strtotime('-15 days')))
                $user_categories_count['silver']++;
            else if( $au->delivery_date <= date('Y-m-d', strtotime('-22 days')))
                $user_categories_count['bronze']++;
        }

        $users = Customer::where('assigned_partner', $user->id)
            ->orderBy('id', 'desc')
            ->select('id', 'name', 'mobile', 'house_no', 'building', 'street', 'area', 'city', 'state', 'pincode')
            ->paginate(10);

        $uids = $users->map(function($element){
            return $element->id;
        })->toArray();

        $wallet_balances = Wallet::getMultipleBalances($uids);

        foreach($users as $u){
            $u->balance = $wallet_balances[$u->id]??0;
            $u->order_count = $order_count[$u->id]??0;
            $u->last_order_date = $last_order_date[$u->id]??'--';
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('user_categories_count', 'users')
        ];


    }

}
