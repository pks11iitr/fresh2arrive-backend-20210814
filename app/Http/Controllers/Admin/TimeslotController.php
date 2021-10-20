<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
//use App\Models\Category;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeslotController extends Controller
{

    public function index(Request $request){
            $Timeslot = Timeslot::active()->orderBy('id', 'desc')
                ->paginate(10);
        return view('admin.timeslot.view',compact('Timeslot'));
    }

    public  function create(Request $request){
       // $categories = Timeslot::get();
        return view('admin.timeslot.add');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'from_time'=>'required',
            'to_time'=>'required',
            'isactive'=>'required'
        ]);

        $timeslot = TimeSlot::create(array_merge(
            $request->only('name', 'from_time', 'to_time', 'order_till', 'isactive'),
        ));

        return redirect()->route('timeslot.edit', ['id'=>$timeslot->id])->with('success', 'Timeslot has been added');
    }

    public function edit(Request $request, $id){

        $timeslot = TimeSlot::findOrFail($id);

        return view('admin.timeslot.edit', compact('timeslot'));
    }

    public function update(Request $request, $id){


        $request->validate([
            'name'=>'required',
            'from_time'=>'required',
            'to_time'=>'required',
            'isactive'=>'required'
        ]);

        $TimeSlot = TimeSlot::findOrFail($id);

        if($request->image){
            $path = $this->getImagePath($request->image, 'banners');
        }else{
            $path = $TimeSlot->getRawOriginal('image');
        }

        $TimeSlot->update(array_merge(
            $request->only( 'name', 'from_time', 'to_time', 'order_till', 'isactive'),
        ));

        return redirect()->back()->with('success', 'Time Slot has been updated');
    }

}
