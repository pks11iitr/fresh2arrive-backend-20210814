<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Area;
use App\Models\AreaAsign;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    use FileTransfer;

    public  function index(Request $request){

        $areas = Area::active()->select('name','id', 'remarks')
            ->orderby('name', 'asc')
        ->get();

        $search_type=$request->search_type==1?'name':'mobile';
        if($request->search){
            $partners = Partner::where($search_type,'Like',"%$request->search%")
                ->paginate(10);
        }else{
            $partners = Partner::orderBy('id','desc')
                ->paginate(10);
        }
        return view('admin.partners.view',compact('partners','areas'));
    }

    public  function  create(Request $request){
        $area=Area::orderBy('id','desc')
            ->get();
        return view('admin.partners.add',compact('area'));
    }

    public  function store(Request $request){

        $request->validate([
            'name'=>'required',
            'mobile'=>'required',
            'address'=>'required',
            'city'=>'required',
            'pincode'=>'required',
            'state'=>'required',
            'notification_token'=>'required',
            'status'=>'required'
        ]);




        $partners = new  Partner();
        $partners->name=$request->name;
        $partners->mobile=$request->mobile;
        $partners->address=$request->address;
        $partners->city=$request->city;
        $partners->pincode=$request->pincode;
        $partners->state=$request->state;
        $partners->notification_token=$request->notification_token;
        $partners->status=$request->status;
        $partners->support_whatsapp=$request->support_whatsapp;
        $partners->support_mobile=$request->support_mobile;
        $partners->save();


        $partners->areas()->sync($request->area_ids??[]);
        return redirect()->route('partners.edit', $partners->id)
            ->with('success','Partners Addedd Successfully');
        }

    public  function edit(Request $request,$id){
        $areas = Area::get();
        $Partners = Partner::with('areas')->findOrFail($id);
        $assigned_areas=$Partners->areas->map(function($element){
            return $element->id;
        })->toArray();
        return view('admin.partners.edit',compact('Partners', 'areas', 'assigned_areas'));
    }



    public  function update(Request $request,$id){

        $request->validate([
            'name'=>'required',
            'mobile'=>'required',
            'address'=>'required',
            'city'=>'required',
            'pincode'=>'required',
            'state'=>'required',
           // 'notification_token'=>'required',
            'status'=>'required'
        ]);

        $Partners=Partner::findOrFail($id);

        if($request->pan_url){
            $pan=$this->getImagePath($request->pan_url, 'pan');

        }else{
            $pan=$Partners->getRawOriginal('pan_url');

        }

        if($request->aadhaar_url){
            $aadhaar=$this->getImagePath($request->aadhaar_url,'aadhaar');
        }else{
            $aadhaar=$Partners->getRawOriginal('aadhaar_url');
        }



        $Partners->update([
            'name'=>$request->name,
            'mobile'=>$request->mobile,
            'address'=>$request->address,
            'city'=>$request->city,
            'pincode'=>$request->pincode,
            'state'=>$request->state,
           // 'notification_token'=>$request->notification_token,
            'status'=>$request->status,
            'support_whatsapp'=>$request->support_whatsapp,
            'support_mobile'=>$request->support_mobile,
            'pan_no'=>$request->pan_no,
            'aadhaar_no'=>$request->aadhaar_no,
            'pan_url'=>$pan,
            'aadhaar_url'=>$aadhaar,
            'store_name'=>$request->store_name,
            'house_no'=>$request->house_no,
            'landmark'=>$request->landmark

        ]);



        $Partners->areas()->sync($request->area_ids??[]);
        return redirect()->back()->with('success', 'Partners has been updated');

    }


    public function commissions(Request $request){

        $allpartners = Partner::orderBy('id','desc')
            ->get();

        $partners=Partner::orderBy('id','desc')
            ->paginate(20);

        $start1=date("Y-m-d", strtotime("last week monday"));
        $end1=date("Y-m-d", strtotime("last week sunday"));


        $start0=date('Y-m-d', strtotime('+1 days', strtotime($end1)));
        $end0=date('Y-m-d', strtotime('+6 days', strtotime($start0)));


        $start2=date('Y-m-d', strtotime('-7 days', strtotime($start1)));
        $end2=date('Y-m-d', strtotime('+6 days', strtotime($start2)));


        $start3=date('Y-m-d', strtotime('-7 days', strtotime($start2)));
        $end3=date('Y-m-d', strtotime('+6 days', strtotime($start3)));


        $earnings0 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start0)
            ->where('orders.delivery_date', '<=', $end0)
            ->select(DB::raw('sum(round(order_details.commissions*order_details.packet_price*order_details.packet_count/100)) as earnings'), 'delivery_partner')
            ->groupBy('orders.delivery_partner');
        if($request->partner_id)
            $earnings0=$earnings0->where('delivery_parnter', $request->partner_id);
        $earnings0=$earnings0->get();

        $partners_earnings0=[];
        foreach($earnings0 as $earning){
            $partners_earnings0[$earning->delivery_partner]=$earning->earnings;
        }

        $earnings1 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start1)
            ->where('orders.delivery_date', '<=', $end1)
            ->select(DB::raw('sum(round(order_details.commissions*order_details.packet_price*order_details.packet_count/100))  as earnings'), 'delivery_partner')
            ->groupBy('orders.delivery_partner');
        if($request->partner_id)
            $earnings1=$earnings1->where('delivery_parnter', $request->partner_id);
        $earnings1=$earnings1->get();

        $partners_earnings1=[];
        foreach($earnings1 as $earning){
            $partners_earnings1[$earning->delivery_partner]=$earning->earnings;
        }

        $earnings2 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start2)
            ->where('orders.delivery_date', '<=', $end2)
            ->select(DB::raw('sum(round(order_details.commissions*order_details.packet_price*order_details.packet_count/100)) as earnings'), 'delivery_partner')
            ->groupBy('orders.delivery_partner');
        if($request->partner_id)
            $earnings2=$earnings2->where('delivery_parnter', $request->partner_id);
        $earnings2=$earnings2->get();

        $partners_earnings2=[];
        foreach($earnings2 as $earning){
            $partners_earnings2[$earning->delivery_partner]=$earning->earnings;
        }

        $earnings3 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start3)
            ->where('orders.delivery_date', '<=', $end3)
            ->select(DB::raw('sum(round(order_details.commissions*order_details.packet_price*order_details.packet_count/100)) as earnings'), 'delivery_partner')
            ->groupBy('orders.delivery_partner');
       if($request->partner_id)
           $earnings3=$earnings3->where('delivery_parnter', $request->partner_id);
        $earnings3=$earnings3->get();

        $partners_earnings3=[];
        foreach($earnings3 as $earning){
            $partners_earnings3[$earning->delivery_partner]=$earning->earnings;
        }

        return view('admin.partners.commisions', compact('partners', 'partners_earnings0', 'partners_earnings1', 'partners_earnings2', 'partners_earnings3', 'allpartners', 'start0', 'start1', 'start2', 'start3', 'end0', 'end1', 'end2', 'end3'));
    }


}
