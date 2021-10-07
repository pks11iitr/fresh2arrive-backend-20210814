<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public  function  index(Request  $request){
        return view('admin.category.view');
    }

    public  function  create(Request $request){
        return view('admin.category.add');
    }

}
