<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Area;
use App\Models\AreaAsign;
class PartnerController extends Controller
{
    use FileTransfer;

    public  function index(Request $request){
        $search_type=$request->search_type==1?'name':'mobile';
        if($request->search){
            $partners = Partner::where($search_type,'Like',"%$request->search%")
                ->paginate(10);
        }else{
            $partners = Partner::orderBy('id','desc')
                ->paginate(10);
        }

        return view('admin.partners.view',compact('partners'));
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
            'notification_token'=>'required',
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
            'notification_token'=>$request->notification_token,
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


}
