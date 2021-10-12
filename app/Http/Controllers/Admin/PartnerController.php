<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use Illuminate\Http\Request;
use App\Models\Partner;
class PartnerController extends Controller
{
    use FileTransfer;
    public  function index(Request $request){
        if($request->search){
            $partners = Partner::where('name','Like',"%$request->search%")
                ->paginate(10);
        }else{
            $partners = Partner::orderBy('id','desc')
                ->paginate(10);
        }

        return view('admin.partners.view',compact('partners'));
    }

    public  function  create(Request $request){

        return view('admin.partners.add');
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
        $partners->save();

        return redirect()->route('partners.edit', $partners->id)
            ->with('success','Banner Addedd Successfully');
    }


    public  function edit(Request $request,$id){
        $Partners = Partner::findOrFail($id);
        return view('admin.partners.edit',compact('Partners'));
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
        $Partners->update([
            'name'=>$request->name,
            'mobile'=>$request->mobile,
            'address'=>$request->address,
            'city'=>$request->city,
            'pincode'=>$request->pincode,
            'state'=>$request->state,
            'notification_token'=>$request->notification_token,
            'status'=>$request->status
        ]);

        return redirect()->back()->with('success', 'Partners has been updated');

    }


}
