<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request){
        return view('admin.banners.view');
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
