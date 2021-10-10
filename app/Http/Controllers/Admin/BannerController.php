<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use FileTransfer;

    public function index(Request $request){
        $banners = Banner::active()->orderBy('id', 'desc')
                    ->paginate(10);
        return view('admin.banners.view', compact('banners'));
    }

    public function create(Request $request){
        return view('admin.banners.add');
    }

    public function store(Request $request){

       $request->validate([
           'type'=>'required',
           'image'=>'required',
           'isactive'=>'required'
       ]);
       $Banner =  new Banner();
       $Banner->type= $request->type;
       $Banner->isactive= $request->isactive;
       $Banner->image = $this->getImagePath($request->image, 'banners');
       $Banner->save();
       return redirect()->route('banners.edit', $Banner->id)
           ->with('message','Banner Addedd Successfully');
    }

    public function edit(Request $request, $id){

        $banner=Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'type'=>'required',
            //'image'=>'required',
            'isactive'=>'required'
        ]);
        $banner=Banner::findOrFail($id);

        if($request->image){
            $path = $this->getImagePath($request->image, 'banners');
        }else{
            $path = $banner->getRawOriginal('image');
        }

        $banner->update([
            'isactive'=>$request->isactive,
            'image'=>$path,
            'type'=>$request->type
        ]);

        return redirect()->back()->with('success', 'Banner has been updated');

    }

}
