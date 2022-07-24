<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\Banner;
use App\Models\Customer;
use App\Models\Partner;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Wallet;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
class CustomerController extends Controller
{
    use FileTransfer;


    public function count_customer($pid){

        if($pid>0){
            $count = Customer::where('assigned_partner',$pid)
            -> count();
        }else{
            $count = Customer::count();
        }

        return $count;
    }


    public  function index(Request $request){


        $search_type=$request->search_type==1?'name':'mobile';

        $customer = Customer::withCount(['orders'=>function($orders){
            $orders->where('orders.status', '!=', 'cancelled');
        }])
            ->where('id', '>', 0);

        if($request->search){
            $customer = $customer->where($search_type, 'LIKE', "%$request->search%");
        }

        if($request->partners){

            $customer = $customer->where('assigned_partner',$request->partners);

        }

        $customer = $customer->orderBy('id','desc')
                ->paginate(100); 
        $partnersss=Partner::orderBy('id','desc')
        ->get();
        return view('admin.customers.view',compact('customer','partnersss'));
    }

    public  function create(Request $request){
        return view('admin.customers.add');
    }


    public function store(Request $request){

        $request->validate([
            'mobile'=>'Required',
            'email'=>'Required',
            'password'=>'Required',
            'status'=>'Required',
            'name'=>'Required',
            'image'=>'Required',
            'notification_token'=>'Required',
            'house_no'=>'Required',
            'building'=>'Required',
            'street'=>'Required',
            'area'=>'Required',
            'city'=>'Required',
            'state'=>'Required',
            'pincode'=>'Required',
            'lat'=>'Required',
            'lang'=>'Required',
            'map_address'=>'Required',
            'map_json'=>'Required',
            'assigned_partner'=>'Required',
            //'reffered_by'=>'Required',
            //'reffered_by_partner'=>'Required'
        ]);


        $customer = New Customer();
        $customer->mobile=$request->mobile;
        $customer->email=$request->email;
        $customer->password=$request->password;
        $customer->status=$request->status;
        $customer->name=$request->name;
        $customer->image = $this->getImagePath($request->image, 'customers');
        $customer->notification_token=$request->notification_token;
        $customer->house_no=$request->house_no;
        $customer->building=$request->building;
        $customer->street=$request->street;
        $customer->area=$request->area;
        $customer->city=$request->city;
        $customer->state=$request->state;
        $customer->pincode=$request->pincode;
        $customer->lat=$request->lat;
        $customer->lang=$request->lang;
        $customer->map_address=$request->map_address;
        $customer->map_json=$request->map_json;
        $customer->assigned_partner=$request->assigned_partner;
        $customer->reffered_by=$request->reffered_by;
        $customer->reffered_by_partner=$request->reffered_by_partner;
        $customer->save();
        return redirect()->route('customers.edit', $customer->id)
            ->with('success','Customer Addedd Successfully');
    }


    public function return_customername($customerid){
        $customer = Customer::select("*")
            ->where('id',$customerid)
            ->first();

        if($customer){
            $custname=$customer->name;
        }else{
            $custname="NA";
        }
        return $custname;
    }


    public function edit(Request $request, $id){
        $customer=Customer::findOrFail($id);
        $partners = Partner::select('name', 'id')
            ->orderBy('id', 'desc')
            ->get();
        $customername=$this->return_customername($customer->reffered_by);
        $area = Area::active()->orderby('id','desc')
                ->get();
        return view('admin.customers.edit',['customername'=>$customername], compact('customer', 'partners','area','customername'));
    }







    public function update(Request $request, $id){

        $request->validate([
            'mobile'=>'Required',
           // 'email'=>'Required',
           // 'password'=>'Required',
            'status'=>'Required',
            'name'=>'Required',
            //'image'=>'Required',
           // 'notification_token'=>'Required',
            'house_no'=>'Required',
            'building'=>'Required',
            'street'=>'Required',
            'area'=>'Required',
            'city'=>'Required',
            'state'=>'Required',
            'pincode'=>'Required',
           // 'lat'=>'Required',
           // 'lang'=>'Required',
            'map_address'=>'Required',
           // 'map_json'=>'Required',
            'assigned_partner'=>'Required',
           // 'reffered_by'=>'Required',
            //'reffered_by_partner'=>'Required'
        ]);

        $customer = Customer::findOrfail($id);
        if($request->image){
            $path = $this->getImagePath($request->image, 'customers');
        }else{
            $path = $customer->getRawOriginal('image');
        }


        $customer->update([
            'mobile'=>$request->mobile,
          //  'email'=>$request->email,
           // 'password'=>$request->password,
            'status'=>$request->status,
            'name'=>$request->name,
           // 'image'=>$path,
            'notification_token'=>$request->notification_token,
            'house_no'=>$request->house_no,
            'building'=>$request->building,
            'street'=>$request->street,
            'area'=>$request->area,
            'city'=>$request->city,
            'state'=>$request->state,
            'pincode'=>$request->pincode,
            'lat'=>$request->lat,
            'lang'=>$request->lang,
            'map_address'=>$request->map_address,
            'map_json'=>$request->map_json,
            'assigned_partner'=>$request->assigned_partner,
            //'reffered_by'=>$request->reffered_by,
            //'reffered_by_partner'=>$request->reffered_by_partner
        ]);

        return redirect()->back()->with('success', 'Customer has been updated');


        
    }



    public function export(request $request){
        if($request->fromdate)
            $customers=Customer::whereBetween('created_at', [$request->fromdate,$request->todate])->get();
        
           // return $customers;die;
        return Excel::download(new CustomerExport($customers), 'customers.xlsx');
    }

    public function history($id){
        $wallet=Wallet::where('user_id',$id)->paginate(10);
        return view('admin.customers.wallethistory',compact('wallet'));
    }



}
