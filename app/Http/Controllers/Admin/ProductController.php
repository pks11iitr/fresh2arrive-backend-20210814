<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        return view('admin.products.view');
    }

    public  function create(Request $request){
        return view('admin.products.add');
    }
}
