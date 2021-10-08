<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request){
        $banners = Banner::active()->paginate(10);
        return view('admin.banners.view', compact('banners'));
    }

    public function create(Request $request){
        return view('admin.banners.add');
    }

    public function store(Request $request){

    }

    public function edit(Request $request, $id){
        return view('admin.banners.edit');
    }

    public function update(Request $request, $id){

    }

}
