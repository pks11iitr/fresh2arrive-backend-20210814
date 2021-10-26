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

        $search = $request->search??'';

        // customer with no order
        $inactive_users = Customer::whereDoesntHave('orders', function($orders){
            $orders->whereIn('status', ['processing', 'confirmed', 'processing', 'dispatched', 'delivered']);
        })
                ->where('assigned_partner', $user->id)
                ->count();

        $reported = $user->reportedUsers()->count();

        // customer with atleast one order
        $active_users = Order::join('customers', 'customers.id', '=', 'orders.user_id')
            ->where('assigned_partner', $user->id)
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
        $user_categories_count['all']=$inactive_users + count($active_users);
        foreach($active_users as $au){
            $last_order_date[$au->user_id]=$au->delivery_date;
            $order_count[$au->user_id]=$au->count;
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
        ;

        if($search){
            $users = $users->where(function($customer)use($search){
                $customer->where('name', 'like', '%'.$search.'%')
                    ->orWhere('mobile', 'like', '%'.$search.'%');
                });
        }


        switch($request->customer_type){
            case 'all':
                break;
            case 'platinum':
                $users = $users
//                    ->whereHas('orders', function($orders){
//                    $orders->where('delivery_date', '>=', date('Y-m-d', strtotime('-7 days')))
                    ->whereExists(function($query){

                        $query->where('customers.id', DB::raw('orders.user_id'))
                            ->whereIn('orders.status', ['confirmed', 'processing', 'dispatched', 'delivered'])
                            ->select('user_id')
                            ->having(DB::raw('max(delivery_date)'), '>=', date('Y-m-d', strtotime('-7 days')))
                            ->from('orders')
                            ->groupBy('user_id');

                });
                break;
            case 'gold':
                $users = $users
//                    ->whereHas('orders', function($orders){
//                    $orders->where('delivery_date', '>=', date('Y-m-d', strtotime('-14 days')))
//                        ->where('delivery_date', '<=', date('Y-m-d', strtotime('-8 days')));
//                });
                    ->whereExists(function($query){

                        $query->where('customers.id', DB::raw('orders.user_id'))
                            ->whereIn('orders.status', ['confirmed', 'processing', 'dispatched', 'delivered'])
                            ->select('user_id')
                            ->having(DB::raw('max(delivery_date)'), '>=', date('Y-m-d', strtotime('-14 days')))
                            ->having(DB::raw('max(delivery_date)'), '<=', date('Y-m-d', strtotime('-8 days')))
                            ->from('orders')
                            ->groupBy('user_id');

                    });
                break;
            case 'silver':
                $users = $users
//                    ->whereHas('orders', function($orders){
//                    $orders->where('delivery_date', '>=', date('Y-m-d', strtotime('-21 days')))
//                        ->where('delivery_date', '<=', date('Y-m-d', strtotime('-15 days')));
//
//                });
                    ->whereExists(function($query){

                        $query->where('customers.id', DB::raw('orders.user_id'))
                            ->whereIn('orders.status', ['confirmed', 'processing', 'dispatched', 'delivered'])
                            ->select('user_id')
                            ->having(DB::raw('max(delivery_date)'), '>=', date('Y-m-d', strtotime('-21 days')))
                            ->having(DB::raw('max(delivery_date)'), '<=', date('Y-m-d', strtotime('-15 days')))
                            ->from('orders')
                            ->groupBy('user_id');

                    });
                break;
            case 'bronze':
                $users = $users
//                    ->whereHas('orders', function($orders){
//                    $orders->where('delivery_date', '<=', date('Y-m-d', strtotime('-22 days')));
//                        });
                    ->whereExists(function($query){

                        $query->where('customers.id', DB::raw('orders.user_id'))
                            ->whereIn('orders.status', ['confirmed', 'processing', 'dispatched', 'delivered'])
                            ->select('user_id')
                            ->having(DB::raw('max(delivery_date)'), '<=', date('Y-m-d', strtotime('-22 days')))
                            ->from('orders')
                            ->groupBy('user_id');

                    });


                break;
            case 'slow_moving':
                $users = $users->has('orders', '=', 1);
                break;
            case 'reported':
                break;
            case 'inactive':
                $users = $users->whereDoesntHave('orders', function($orders){
                    $orders->whereIn('status', ['processing', 'confirmed', 'processing', 'dispatched', 'delivered']);
                });
                break;
            default:

        }

        $users = $users->paginate(100);

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
