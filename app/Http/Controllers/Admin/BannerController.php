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

       $Banner =  new Banner();
       $Banner->type= $request->input('type');
       $Banner->isactive= $request->input('isactive');
       $Banner->image = $this->getImagePath($request->image, 'banners');
        $Banner->save();
        return redirect()->back()->with('message','Banner Addedd Successfully');
    }

    public function edit(Request $request, $id){
        return view('admin.banners.edit');
    }

    public function update(Request $request, $id){

    }

}
