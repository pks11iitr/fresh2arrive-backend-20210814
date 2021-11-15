<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AreaImport;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Models\Area;
use Maatwebsite\Excel\Excel;
use mysql_xdevapi\TableUpdate;

class AreaController extends Controller
{
    public  function index(Request $request){
        $areas=Area::orderBy('id','desc')
            ->paginate(100);
        return view('admin.area.view', compact('areas'));
    }

    public function create(Request $request){
        return view('admin.area.add');
    }

    public function store(Request $request){
        $request->validate([
                'name'=>'required',
                'city'=>'required',
                'state'=>'required',
                'pincode'=>'required',
               'isactive'=>'required'
        ]);

        $Area= new Area();
        $Area->name=$request->name;
        $Area->city=$request->city;
        $Area->state=$request->state;
        $Area->pincode=$request->pincode;
        $Area->isactive=$request->isactive;
        $Area->save();
        return redirect()->route('area.edit', $Area->id)
            ->with('success','Area Addedd Successfully');
    }


    public  function edit(Request $request,$id){
        $area=Area::findOrFail($id);
        return view('admin.area.edit', compact('area'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'city'=>'required',
            'state'=>'required',
            'pincode'=>'required',
            'isactive'=>'required'
        ]);
        $area=Area::findOrFail($id);
        $area->update([
            'name'=>$request->name,
            'city'=>$request->city,
            'state'=>$request->state,
            'pincode'=>$request->pincode,
            'isactive'=>$request->isactive
        ]);
        return redirect()->back()->with('success', 'Area has been updated');
    }

    public  function import_Excel(Request $request){
        return view('admin.area.import');
    }

    public  function store_excel(Request $request){
        $request->validate([
            'file'=>'required'
        ]);
        \Maatwebsite\Excel\Facades\Excel::import(new AreaImport(), $request->file);
        //$Area->save();
        return redirect()->route('area.import')
            ->with('success','Area Addedd Successfully');
        //return view('admin.area.import');
    }


}
